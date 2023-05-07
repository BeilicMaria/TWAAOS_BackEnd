<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unsigned();
            $table->string('reason');
            $table->date('date');
            $table->integer('FK_studentId')->unsigned();
            $table->foreign('FK_studentId')
                ->references('id')->on('users');
            $table->integer('FK_secretaryId')->unsigned();
            $table->foreign('FK_secretaryId')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
