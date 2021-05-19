<?php

use Illuminate\Database\Seeder;

class AnnouncementTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('announcements')->delete();
        
        \DB::table('announcements')->insert(array (
            0 => 
            array (
                'id' => 1,
                'start_date' => '2020-03-31',
                'end_date' => '9999-12-31',
                'title' => 'System Downtime Schedule',
                'announcement' => '<p><span style=""><b><u>SYSTEM DOWNTIME</u></b></span></p><p><span style=""><br></span><br></p><p><span style="">Dear user,</span></p><p><span style=""><br></span><br></p><p>We wish to inform that effective <b>1 August 2019</b>, system will down from 5.00 am to 12.59 am (peak hour) and from 1.00 am to 4.59 am (off-peak hour) respectively.&nbsp;</p><p><span style=""><br></span><br></p><p><span style="">We apologize for any inconvenience this may cause. Your understanding is greatly appreciated.&nbsp;</span></p><p><span style=""><br></span><br></p><p><span style="">Thank you.</span></p>',
                'created_by' => '55326',
                'created_at' => '2020-03-16 14:39:29',
                'updated_at' => '2020-03-16 19:23:11',
            ),
        ));
        
        
    }
}