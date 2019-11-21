<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_option', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('weekday_fee', 20)->nullable();
            $table->string('holiday_fee', 20)->nullable();
            $table->text('memo')->nullable();
            $table->string('weekday_benefits', 20)->nullable();
            $table->string('holiday_benefits', 20)->nullable();
            $table->integer('campaign_id')->unsigned();
            $table->boolean('is_deleted')->default(false);
            $table->text('content')->nullable();  // json save
            $table->timestamps();
        });

        Schema::table('campaign_option', function(Blueprint $table) {
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
        Schema::table('campaign_option', function(Blueprint $table) {
            $table->dropForeign('campaign_option_campaign_id_foreign');
        });

        Schema::dropIfExists('campaign_option');
    }
}
