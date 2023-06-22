<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCertificates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->integer('FK_secretaryId')->unsigned()->nullable()->change();
            $table->integer('number')->unsigned()->nullable()->change();
            if (!Schema::hasColumns("certificates", [
                "status"
            ])) {
                $table->integer('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumns("certificates", [
                "status",
            ])) {
                $table->dropColumn("status");
            }
        });
    }
}
