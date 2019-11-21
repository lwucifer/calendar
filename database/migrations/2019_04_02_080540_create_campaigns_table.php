<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('web_name', 200);
            $table->integer('time');
            $table->integer('photo_id')->unsigned();
            $table->boolean('is_display_calendar')->nullable();
            $table->boolean('is_enable')->default(true);
            $table->text('comment')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            $table->integer('last_update_by')->nullable();
        });

        Schema::table('campaigns', function(Blueprint $table) {
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
        Schema::table('campaigns', function(Blueprint $table) {
            $table->dropForeign('campaigns_photo_id_foreign');
        });

        Schema::drop('campaigns');
    }
}
