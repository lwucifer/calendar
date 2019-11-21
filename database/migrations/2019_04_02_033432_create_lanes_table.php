<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lanes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200)->nullable();
            $table->integer('order')->nullable();;
            $table->time('weekday_start_time')->nullable();;
            $table->time('weekday_end_time')->nullable();
            $table->time('holiday_start_time')->nullable();
            $table->time('holiday_end_time')->nullable();
            $table->integer('visit_time')->nullable();
            $table->integer('store_id')->unsigned();
            $table->boolean('is_deleted')->default(false)->nullable();
            $table->timestamps();
        });

        Schema::table('lanes', function(Blueprint $table) {
            $table->foreign('store_id')
                ->references('id')
                ->on('store')
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
        Schema::table('lanes', function(Blueprint $table) {
            $table->dropForeign('lanes_store_id_foreign');
        });

        Schema::drop('lanes');
    }
}
