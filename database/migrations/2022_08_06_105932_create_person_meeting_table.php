<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_meeting', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('person_id')->unsigned();
            $table->unsignedBigInteger('meeting_id')->unsigned();
            $table->foreign('person_id')->references('id')->on('persons')
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
        Schema::dropIfExists('person_meeting');
    }
};
