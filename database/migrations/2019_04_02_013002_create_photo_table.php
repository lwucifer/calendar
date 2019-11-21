<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->boolean('is_enable')->default(true);
            $table->integer('cash_id')->unsigned();
            $table->text('comment');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            $table->integer('last_update_by');
        });

        Schema::table('photo', function(Blueprint $table) {
            $table->foreign('cash_id')
                ->references('id')
                ->on('cash_departs')
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
        Schema::table('photo', function(Blueprint $table) {
            $table->dropForeign('photo_cash_id_foreign');
        });

        Schema::drop('photo');
    }
}
