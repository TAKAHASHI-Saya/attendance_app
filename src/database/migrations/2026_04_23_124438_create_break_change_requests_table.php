<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('break_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_change_request_id')->constrained();
            $table->foreignId('rest_break_id')->nullable();
            $table->time('after_break_in_at')->nullable();
            $table->time('after_break_out_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('break_change_requests');
    }
}
