<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftPatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_patterns', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->timestamps();
            $table->string('code', 10);
            $table->string('description');
            $table->integer('created_by');
            $table->tinyInteger('days_count')->default(0);
            $table->float('total_hours', 5, 2)->default(0.0);
            $table->smallInteger('total_minutes')->default(0);
            $table->softDeletes();
            $table->integer('last_edited_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_patterns');
    }
}
