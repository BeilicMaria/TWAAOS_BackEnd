<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProgramsDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs_domains', function (Blueprint $table) {
            $table->integer('FK_programId')->unsigned();
            $table->foreign('FK_programId')
                ->references('id')->on('programs');
            $table->integer('FK_domainId')->unsigned();
            $table->foreign('FK_domainId')
                ->references('id')->on('domains');
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
