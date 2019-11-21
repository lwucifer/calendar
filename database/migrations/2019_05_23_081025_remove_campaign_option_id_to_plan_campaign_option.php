<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCampaignOptionIdToPlanCampaignOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan_campaign_option', function (Blueprint $table) {
            $table->dropForeign('plan_campaign_option_campaign_option_id_foreign');
            $table->dropColumn('campaign_option_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_campaign_option', function (Blueprint $table) {
            //
        });
    }
}
