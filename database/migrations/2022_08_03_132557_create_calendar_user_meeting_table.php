<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_user_meeting', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('calendar_user_id')->unsigned();
            $table->unsignedBigInteger('meeting_id')->unsigned();
            $table->foreign('calendar_user_id')->references('id')->on('calendar_users')
                ->onDelete('cascade');
            $table->foreign('meeting_id')->references('id')->on('meetings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_user_meeting');
    }
};
