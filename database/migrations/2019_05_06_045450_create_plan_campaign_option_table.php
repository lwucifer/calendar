<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanCampaignOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_campaign_option', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->integer('campaign_option_id')->unsigned();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('plan_campaign_option', function(Blueprint $table) {
            $table->foreign('plan_id')->references('id')->on('plan')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('campaign_option_id')->references('id')->on('campaign_option')
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
        Schema::table('plan_campaign_option', function(Blueprint $table) {
            $table->dropForeign('plan_campaign_option_plan_id_foreign');
            $table->dropForeign('plan_campaign_option_campaign_option_id_foreign');
        });

        Schema::dropIfExists('plan_campaign_option');
    }
}
