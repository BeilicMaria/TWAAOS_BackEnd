<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumns("users", [
                "lastName", "firstName", "phone",  "FK_roleId"
            ])) {
                $table->string('lastName');
                $table->string('firstName');
                $table->string('collegeDegree');
                $table->string('program');
                $table->string('year');
                $table->string('financialStatus');
                $table->integer('FK_roleId')->unsigned();
                $table->foreign('FK_roleId')
                    ->references('id')->on('user_roles');

                $table->string('provider_id')->nullable();
                $table->string('avatar')->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumns("users", [
                "fullName",  "userName", "phone",
                "FK_addressId",  "FK_roleId"
            ])) {
                $table->dropColumn("fullName");
                $table->dropColumn("userName");
                $table->dropColumn("phone");
                $table->dropColumn("FK_addressId");
                $table->dropColumn("FK_roleId");
            }
        });
    }
}
