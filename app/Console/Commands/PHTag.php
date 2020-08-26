<?php

namespace App\Console\Commands;

use App\User;
use App\UserRecord;
use App\DayType;
use App\WsrChangeReq;
use App\UserShiftPattern;
use App\ShiftPlan;
use App\ShiftPlanStaffDay;
use App\ShiftPattern;
use Illuminate\Console\Command;

class PHTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tag:ph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tag PH day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $todate = date("Y-m-d");
        // $check
    }
}
