<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsrChangeReqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsr_change_reqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('user_id');
            $table->smallInteger('shift_pattern_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->bigInteger('superior_id');
            $table->string('status', 10)->default('New');
            $table->string('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wsr_change_reqs');
    }
}
