<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeCampaignMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_mail', function (Blueprint $table) {
            $table->integer('day')->after('send_time');
            $table->integer('type')->after('send_time');
            $table->dropColumn('before_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_mail', function($table) {
            $table->dropColumn('before_day');
        });
    }
}
