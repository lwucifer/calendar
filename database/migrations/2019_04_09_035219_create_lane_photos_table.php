<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lane_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lane_id')->unsigned();
            $table->integer('photo_id')->unsigned();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('lane_photos', function(Blueprint $table) {
            $table->foreign('lane_id')->references('id')->on('lanes')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('photo_id')->references('id')->on('photo')
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
        Schema::table('lane_photos', function(Blueprint $table) {
            $table->dropForeign('lane_photos_lane_id_foreign');
            $table->dropForeign('lane_photos_photo_id_foreign');
        });

        Schema::dropIfExists('lane_photos');
    }
}
