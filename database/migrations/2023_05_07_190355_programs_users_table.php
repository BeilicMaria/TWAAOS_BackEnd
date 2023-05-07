<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProgramsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs_users', function (Blueprint $table) {
            $table->id();
            $table->integer('FK_programId')->unsigned();
            $table->foreign('FK_programId')
                ->references('id')->on('programs');
            $table->integer('FK_userId')->unsigned();
            $table->foreign('FK_userId')
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
