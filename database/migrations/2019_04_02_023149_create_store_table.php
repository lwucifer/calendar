<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('phone', 12)->nullable();
            $table->integer('manager_id');
            $table->time('weekday_start_time')->nullable();
            $table->time('weekday_end_time')->nullable();
            $table->time('holiday_start_time')->nullable();
            $table->time('holiday_end_time')->nullable();
            $table->boolean('day_off_monday')->nullable();
            $table->boolean('day_off_tuesday')->nullable();
            $table->boolean('day_off_wednesday')->nullable();
            $table->boolean('day_off_thursday')->nullable();
            $table->boolean('day_off_friday')->nullable();
            $table->boolean('day_off_saturday')->nullable();
            $table->boolean('day_off_sunday')->nullable();
            $table->boolean('is_enable')->default(true)->nullable();
            $table->string('fixed_days_off', 255)->nullable();
            $table->string('fixed_days_on', 255)->nullable();
            $table->text('sign_email')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('is_deleted')->default(false)->nullable();
            $table->timestamps();
            $table->integer('last_update_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('store');
    }
}
