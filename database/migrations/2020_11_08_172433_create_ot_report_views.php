<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtReportViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("
        CREATE VIEW views_overall_report AS
        (
          SELECT er.user_id as user_id, e.id AS entities_id, 
              c.status_id AS status_id, s.name AS status_name
  
          FROM `user_roles` er
            LEFT JOIN elists e ON e.id=er.entities_id
            LEFT JOIN `clists` c ON c.id=e.checklists_id
            LEFT JOIN `status` s ON s.id = c.overall_status_id
  
          WHERE s.slug = 'completed'
            AND c.deleted_at IS NULL
        )
      ");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ot_report_views');
    }
}
