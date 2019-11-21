<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_options', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content'); // json save
            $table->integer('photo_id')->unsigned();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('photo_options', function(Blueprint $table) {
            $table->foreign('photo_id')
                ->references('id')
                ->on('photo')
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
        Schema::table('photo_options', function(Blueprint $table) {
            $table->dropForeign('photo_options_photo_id_foreign');
        });

        Schema::drop('photo_options');
    }
}
