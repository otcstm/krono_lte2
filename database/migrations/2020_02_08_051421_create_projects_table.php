<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('project_no',40);
          $table->string('descr', 100)->nullable();
          $table->string('status', 10)->nullable();
          $table->string('type', 10)->nullable();
          $table->string('cost_center', 20)->nullable();
          $table->string('company_code', 10)->nullable();
          $table->string('network_header', 20)->nullable();
          $table->string('network_headerdescr',100)->nullable();
          $table->string('network_act_no',10)->nullable();
          $table->string('network_act_descr',100)->nullable();
          $table->string('approver_id',20)->nullable();
          $table->decimal('budget',10,2)->default(0.00);
          $table->dateTime('upd_dm')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
