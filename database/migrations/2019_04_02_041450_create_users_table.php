<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->integer('store_id')->unsigned()->nullable();
            $table->boolean('is_enable')->default(true);
            $table->string('username', 30);
            $table->string('email');
            $table->string('password', 60);
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->string('kana_first_name', 20);
            $table->string('kana_last_name',20);
            $table->string('phone', 12);
            $table->string('zip_code', 10)->nullable();
            $table->string('address1', 200)->nullable();
            $table->string('address2', 200)->nullable();
            $table->string('other1', 200)->nullable();
            $table->string('other2', 200)->nullable();
            $table->text('comment')->nullable();
            $table->integer('parent_user')->nullable();
            $table->rememberToken();
            $table->boolean('is_deleted')->default(false)->nullable();;
            $table->timestamps();
            $table->integer('last_update_by')->nullable();;
        });

        Schema::table('users', function(Blueprint $table) {
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('store_id')
                ->references('id')
                ->on('store')
                ->onDelete('set null')
                ->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropForeign('users_store_id_foreign');
        });

        Schema::drop('users');
    }
}
