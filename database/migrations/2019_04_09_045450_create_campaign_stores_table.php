<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->unsigned();
            $table->integer('store_id')->unsigned();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('campaign_stores', function(Blueprint $table) {
            $table->foreign('campaign_id')->references('id')->on('campaigns')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('store_id')->references('id')->on('store')
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
        Schema::table('campaign_stores', function(Blueprint $table) {
            $table->dropForeign('campaign_stores_campaign_id_foreign');
            $table->dropForeign('campaign_stores_store_id_foreign');
        });

        Schema::dropIfExists('campaign_stores');
    }
}
