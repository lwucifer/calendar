<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->text('memo');
            $table->integer('user_id')->unsigned();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('f_numbers', function(Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('f_numbers', function(Blueprint $table) {
            $table->dropForeign('f_numbers_user_id_foreign');
        });

        Schema::drop('f_numbers');
    }
}
