<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned();
            $table->integer('campaign_id')->unsigned();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('status');
            $table->integer('user_id')->unsigned();
            $table->text('comment')->nullable();
            $table->boolean('is_enable')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            $table->integer('last_update_by')->nullable();
        });

        Schema::table('plan', function(Blueprint $table) {
            $table->foreign('store_id')
                ->references('id')
                ->on('store')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::table('plan', function(Blueprint $table) {
            $table->dropForeign('plan_store_id_foreign');
            $table->dropForeign('plan_campaign_id_foreign');
            $table->dropForeign('plan_user_id_foreign');
        });

        Schema::drop('plan');
    }
}
