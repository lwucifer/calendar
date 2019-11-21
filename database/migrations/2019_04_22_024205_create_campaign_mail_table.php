<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_mail', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('send_time')->nullable();
            $table->integer('before_day')->nullable();
            $table->string('template', 20)->nullable();
            $table->integer('campaign_id')->unsigned();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('campaign_mail', function(Blueprint $table) {
            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
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
        Schema::table('campaign_mail', function(Blueprint $table) {
            $table->dropForeign('campaign_mail_campaign_id_foreign');
        });

        Schema::dropIfExists('campaign_mail');
    }
}
