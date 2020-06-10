<?php

use Illuminate\Database\Seeder;

class ShiftPatternDaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shift_pattern_days')->delete();
        
        \DB::table('shift_pattern_days')->insert(array (
            0 => 
            array (
                'id' => 1,
                'created_at' => '2019-11-13 17:21:15',
                'updated_at' => '2019-11-13 17:21:15',
                'shift_pattern_id' => 10,
                'day_seq' => 1,
                'day_type_id' => 19,
            ),
            1 => 
            array (
                'id' => 2,
                'created_at' => '2019-11-13 17:22:15',
                'updated_at' => '2019-11-13 17:22:15',
                'shift_pattern_id' => 10,
                'day_seq' => 2,
                'day_type_id' => 19,
            ),
            2 => 
            array (
                'id' => 3,
                'created_at' => '2019-11-13 17:22:32',
                'updated_at' => '2019-11-13 17:22:32',
                'shift_pattern_id' => 10,
                'day_seq' => 3,
                'day_type_id' => 19,
            ),
            3 => 
            array (
                'id' => 4,
                'created_at' => '2019-11-13 17:22:47',
                'updated_at' => '2019-11-13 17:22:47',
                'shift_pattern_id' => 10,
                'day_seq' => 4,
                'day_type_id' => 19,
            ),
            4 => 
            array (
                'id' => 8,
                'created_at' => '2019-11-14 15:10:34',
                'updated_at' => '2019-11-14 15:10:34',
                'shift_pattern_id' => 2,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            5 => 
            array (
                'id' => 9,
                'created_at' => '2019-11-14 15:10:38',
                'updated_at' => '2019-11-14 15:10:38',
                'shift_pattern_id' => 2,
                'day_seq' => 2,
                'day_type_id' => 2,
            ),
            6 => 
            array (
                'id' => 10,
                'created_at' => '2019-11-14 15:10:46',
                'updated_at' => '2019-11-14 15:10:46',
                'shift_pattern_id' => 2,
                'day_seq' => 3,
                'day_type_id' => 2,
            ),
            7 => 
            array (
                'id' => 11,
                'created_at' => '2019-11-14 15:10:55',
                'updated_at' => '2019-11-14 15:10:55',
                'shift_pattern_id' => 2,
                'day_seq' => 4,
                'day_type_id' => 2,
            ),
            8 => 
            array (
                'id' => 12,
                'created_at' => '2019-11-14 15:11:15',
                'updated_at' => '2019-11-14 15:11:15',
                'shift_pattern_id' => 2,
                'day_seq' => 5,
                'day_type_id' => 3,
            ),
            9 => 
            array (
                'id' => 13,
                'created_at' => '2019-11-14 15:11:28',
                'updated_at' => '2019-11-14 15:11:28',
                'shift_pattern_id' => 2,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            10 => 
            array (
                'id' => 14,
                'created_at' => '2019-11-14 15:11:41',
                'updated_at' => '2019-11-14 15:11:41',
                'shift_pattern_id' => 2,
                'day_seq' => 7,
                'day_type_id' => 5,
            ),
            11 => 
            array (
                'id' => 15,
                'created_at' => '2019-11-14 15:13:41',
                'updated_at' => '2019-11-14 15:13:41',
                'shift_pattern_id' => 3,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            12 => 
            array (
                'id' => 16,
                'created_at' => '2019-11-14 15:13:47',
                'updated_at' => '2019-11-14 15:13:47',
                'shift_pattern_id' => 3,
                'day_seq' => 2,
                'day_type_id' => 2,
            ),
            13 => 
            array (
                'id' => 17,
                'created_at' => '2019-11-14 15:14:07',
                'updated_at' => '2019-11-14 15:14:07',
                'shift_pattern_id' => 3,
                'day_seq' => 3,
                'day_type_id' => 2,
            ),
            14 => 
            array (
                'id' => 18,
                'created_at' => '2019-11-14 15:14:17',
                'updated_at' => '2019-11-14 15:14:17',
                'shift_pattern_id' => 3,
                'day_seq' => 4,
                'day_type_id' => 6,
            ),
            15 => 
            array (
                'id' => 19,
                'created_at' => '2019-11-14 15:14:36',
                'updated_at' => '2019-11-14 15:14:36',
                'shift_pattern_id' => 3,
                'day_seq' => 5,
                'day_type_id' => 5,
            ),
            16 => 
            array (
                'id' => 20,
                'created_at' => '2019-11-14 15:14:47',
                'updated_at' => '2019-11-14 15:14:47',
                'shift_pattern_id' => 3,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            17 => 
            array (
                'id' => 21,
                'created_at' => '2019-11-14 15:14:56',
                'updated_at' => '2019-11-14 15:14:56',
                'shift_pattern_id' => 3,
                'day_seq' => 7,
                'day_type_id' => 2,
            ),
            18 => 
            array (
                'id' => 22,
                'created_at' => '2019-11-14 15:16:18',
                'updated_at' => '2019-11-14 15:16:18',
                'shift_pattern_id' => 4,
                'day_seq' => 1,
                'day_type_id' => 7,
            ),
            19 => 
            array (
                'id' => 23,
                'created_at' => '2019-11-14 15:16:27',
                'updated_at' => '2019-11-14 15:16:27',
                'shift_pattern_id' => 4,
                'day_seq' => 2,
                'day_type_id' => 7,
            ),
            20 => 
            array (
                'id' => 24,
                'created_at' => '2019-11-14 15:16:42',
                'updated_at' => '2019-11-14 15:16:42',
                'shift_pattern_id' => 4,
                'day_seq' => 3,
                'day_type_id' => 7,
            ),
            21 => 
            array (
                'id' => 25,
                'created_at' => '2019-11-14 15:16:56',
                'updated_at' => '2019-11-14 15:16:56',
                'shift_pattern_id' => 4,
                'day_seq' => 4,
                'day_type_id' => 7,
            ),
            22 => 
            array (
                'id' => 26,
                'created_at' => '2019-11-14 15:17:31',
                'updated_at' => '2019-11-14 15:17:31',
                'shift_pattern_id' => 4,
                'day_seq' => 5,
                'day_type_id' => 3,
            ),
            23 => 
            array (
                'id' => 27,
                'created_at' => '2019-11-14 15:17:56',
                'updated_at' => '2019-11-14 15:17:56',
                'shift_pattern_id' => 4,
                'day_seq' => 6,
                'day_type_id' => 11,
            ),
            24 => 
            array (
                'id' => 28,
                'created_at' => '2019-11-14 15:18:15',
                'updated_at' => '2019-11-14 15:18:15',
                'shift_pattern_id' => 4,
                'day_seq' => 7,
                'day_type_id' => 5,
            ),
            25 => 
            array (
                'id' => 29,
                'created_at' => '2019-11-14 16:15:31',
                'updated_at' => '2019-11-14 16:15:31',
                'shift_pattern_id' => 11,
                'day_seq' => 1,
                'day_type_id' => 21,
            ),
            26 => 
            array (
                'id' => 30,
                'created_at' => '2019-11-14 16:15:49',
                'updated_at' => '2019-11-14 16:15:49',
                'shift_pattern_id' => 11,
                'day_seq' => 2,
                'day_type_id' => 21,
            ),
            27 => 
            array (
                'id' => 31,
                'created_at' => '2019-11-14 16:15:58',
                'updated_at' => '2019-11-14 16:15:58',
                'shift_pattern_id' => 11,
                'day_seq' => 3,
                'day_type_id' => 21,
            ),
            28 => 
            array (
                'id' => 32,
                'created_at' => '2019-11-14 16:16:12',
                'updated_at' => '2019-11-14 16:16:12',
                'shift_pattern_id' => 11,
                'day_seq' => 4,
                'day_type_id' => 21,
            ),
            29 => 
            array (
                'id' => 33,
                'created_at' => '2019-11-14 16:16:23',
                'updated_at' => '2019-11-14 16:16:23',
                'shift_pattern_id' => 11,
                'day_seq' => 5,
                'day_type_id' => 21,
            ),
            30 => 
            array (
                'id' => 34,
                'created_at' => '2019-11-14 16:16:39',
                'updated_at' => '2019-11-14 16:16:39',
                'shift_pattern_id' => 11,
                'day_seq' => 6,
                'day_type_id' => 21,
            ),
            31 => 
            array (
                'id' => 35,
                'created_at' => '2019-11-14 16:20:06',
                'updated_at' => '2019-11-14 16:20:06',
                'shift_pattern_id' => 12,
                'day_seq' => 1,
                'day_type_id' => 22,
            ),
            32 => 
            array (
                'id' => 36,
                'created_at' => '2019-11-14 16:20:18',
                'updated_at' => '2019-11-14 16:20:18',
                'shift_pattern_id' => 12,
                'day_seq' => 2,
                'day_type_id' => 22,
            ),
            33 => 
            array (
                'id' => 37,
                'created_at' => '2019-11-14 16:20:31',
                'updated_at' => '2019-11-14 16:20:31',
                'shift_pattern_id' => 12,
                'day_seq' => 3,
                'day_type_id' => 22,
            ),
            34 => 
            array (
                'id' => 38,
                'created_at' => '2019-11-14 16:20:45',
                'updated_at' => '2019-11-14 16:20:45',
                'shift_pattern_id' => 12,
                'day_seq' => 4,
                'day_type_id' => 22,
            ),
            35 => 
            array (
                'id' => 39,
                'created_at' => '2019-11-14 16:21:01',
                'updated_at' => '2019-11-14 16:21:01',
                'shift_pattern_id' => 12,
                'day_seq' => 5,
                'day_type_id' => 22,
            ),
            36 => 
            array (
                'id' => 40,
                'created_at' => '2019-11-14 16:21:08',
                'updated_at' => '2019-11-14 16:21:08',
                'shift_pattern_id' => 12,
                'day_seq' => 6,
                'day_type_id' => 22,
            ),
            37 => 
            array (
                'id' => 41,
                'created_at' => '2019-11-14 16:22:44',
                'updated_at' => '2019-11-14 16:22:44',
                'shift_pattern_id' => 14,
                'day_seq' => 1,
                'day_type_id' => 21,
            ),
            38 => 
            array (
                'id' => 42,
                'created_at' => '2019-11-14 16:22:52',
                'updated_at' => '2019-11-14 16:22:52',
                'shift_pattern_id' => 14,
                'day_seq' => 2,
                'day_type_id' => 21,
            ),
            39 => 
            array (
                'id' => 43,
                'created_at' => '2019-11-14 16:23:06',
                'updated_at' => '2019-11-14 16:23:06',
                'shift_pattern_id' => 14,
                'day_seq' => 3,
                'day_type_id' => 21,
            ),
            40 => 
            array (
                'id' => 44,
                'created_at' => '2019-11-14 16:23:14',
                'updated_at' => '2019-11-14 16:23:14',
                'shift_pattern_id' => 14,
                'day_seq' => 4,
                'day_type_id' => 21,
            ),
            41 => 
            array (
                'id' => 45,
                'created_at' => '2019-11-14 16:23:31',
                'updated_at' => '2019-11-14 16:23:31',
                'shift_pattern_id' => 14,
                'day_seq' => 5,
                'day_type_id' => 21,
            ),
            42 => 
            array (
                'id' => 46,
                'created_at' => '2019-11-14 16:23:40',
                'updated_at' => '2019-11-14 16:23:40',
                'shift_pattern_id' => 14,
                'day_seq' => 6,
                'day_type_id' => 21,
            ),
            43 => 
            array (
                'id' => 47,
                'created_at' => '2019-11-15 15:44:37',
                'updated_at' => '2019-11-15 15:44:37',
                'shift_pattern_id' => 5,
                'day_seq' => 1,
                'day_type_id' => 12,
            ),
            44 => 
            array (
                'id' => 48,
                'created_at' => '2019-11-15 15:44:46',
                'updated_at' => '2019-11-15 15:44:46',
                'shift_pattern_id' => 5,
                'day_seq' => 2,
                'day_type_id' => 12,
            ),
            45 => 
            array (
                'id' => 49,
                'created_at' => '2019-11-15 15:45:16',
                'updated_at' => '2019-11-15 15:45:16',
                'shift_pattern_id' => 5,
                'day_seq' => 3,
                'day_type_id' => 12,
            ),
            46 => 
            array (
                'id' => 50,
                'created_at' => '2019-11-15 15:47:38',
                'updated_at' => '2019-11-15 15:47:38',
                'shift_pattern_id' => 5,
                'day_seq' => 4,
                'day_type_id' => 9,
            ),
            47 => 
            array (
                'id' => 51,
                'created_at' => '2019-11-15 15:48:02',
                'updated_at' => '2019-11-15 15:48:02',
                'shift_pattern_id' => 5,
                'day_seq' => 5,
                'day_type_id' => 4,
            ),
            48 => 
            array (
                'id' => 52,
                'created_at' => '2019-11-15 15:48:13',
                'updated_at' => '2019-11-15 15:48:13',
                'shift_pattern_id' => 5,
                'day_seq' => 6,
                'day_type_id' => 12,
            ),
            49 => 
            array (
                'id' => 53,
                'created_at' => '2019-11-15 15:48:32',
                'updated_at' => '2019-11-15 15:48:32',
                'shift_pattern_id' => 5,
                'day_seq' => 7,
                'day_type_id' => 12,
            ),
            50 => 
            array (
                'id' => 54,
                'created_at' => '2019-11-19 11:38:04',
                'updated_at' => '2019-11-19 11:38:04',
                'shift_pattern_id' => 6,
                'day_seq' => 1,
                'day_type_id' => 4,
            ),
            51 => 
            array (
                'id' => 55,
                'created_at' => '2019-11-19 11:38:15',
                'updated_at' => '2019-11-19 11:38:15',
                'shift_pattern_id' => 6,
                'day_seq' => 2,
                'day_type_id' => 5,
            ),
            52 => 
            array (
                'id' => 56,
                'created_at' => '2019-11-19 11:38:28',
                'updated_at' => '2019-11-19 11:38:28',
                'shift_pattern_id' => 6,
                'day_seq' => 3,
                'day_type_id' => 2,
            ),
            53 => 
            array (
                'id' => 57,
                'created_at' => '2019-11-19 11:38:35',
                'updated_at' => '2019-11-19 11:38:35',
                'shift_pattern_id' => 6,
                'day_seq' => 4,
                'day_type_id' => 2,
            ),
            54 => 
            array (
                'id' => 58,
                'created_at' => '2019-11-19 11:38:57',
                'updated_at' => '2019-11-19 11:38:57',
                'shift_pattern_id' => 6,
                'day_seq' => 5,
                'day_type_id' => 3,
            ),
            55 => 
            array (
                'id' => 59,
                'created_at' => '2019-11-19 11:39:04',
                'updated_at' => '2019-11-19 11:39:04',
                'shift_pattern_id' => 6,
                'day_seq' => 6,
                'day_type_id' => 2,
            ),
            56 => 
            array (
                'id' => 60,
                'created_at' => '2019-11-19 11:39:19',
                'updated_at' => '2019-11-19 11:39:19',
                'shift_pattern_id' => 6,
                'day_seq' => 7,
                'day_type_id' => 2,
            ),
            57 => 
            array (
                'id' => 61,
                'created_at' => '2019-11-19 11:41:28',
                'updated_at' => '2019-11-19 11:41:28',
                'shift_pattern_id' => 7,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            58 => 
            array (
                'id' => 62,
                'created_at' => '2019-11-19 11:41:33',
                'updated_at' => '2019-11-19 11:41:33',
                'shift_pattern_id' => 7,
                'day_seq' => 2,
                'day_type_id' => 2,
            ),
            59 => 
            array (
                'id' => 63,
                'created_at' => '2019-11-19 11:41:38',
                'updated_at' => '2019-11-19 11:41:38',
                'shift_pattern_id' => 7,
                'day_seq' => 3,
                'day_type_id' => 2,
            ),
            60 => 
            array (
                'id' => 64,
                'created_at' => '2019-11-19 11:41:57',
                'updated_at' => '2019-11-19 11:41:57',
                'shift_pattern_id' => 7,
                'day_seq' => 4,
                'day_type_id' => 2,
            ),
            61 => 
            array (
                'id' => 65,
                'created_at' => '2019-11-19 11:42:17',
                'updated_at' => '2019-11-19 11:42:17',
                'shift_pattern_id' => 7,
                'day_seq' => 5,
                'day_type_id' => 18,
            ),
            62 => 
            array (
                'id' => 66,
                'created_at' => '2019-11-19 11:42:38',
                'updated_at' => '2019-11-19 11:42:38',
                'shift_pattern_id' => 7,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            63 => 
            array (
                'id' => 67,
                'created_at' => '2019-11-19 11:42:57',
                'updated_at' => '2019-11-19 11:42:57',
                'shift_pattern_id' => 7,
                'day_seq' => 7,
                'day_type_id' => 5,
            ),
            64 => 
            array (
                'id' => 68,
                'created_at' => '2019-11-29 09:59:45',
                'updated_at' => '2019-11-29 09:59:45',
                'shift_pattern_id' => 15,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            65 => 
            array (
                'id' => 69,
                'created_at' => '2019-11-29 10:00:17',
                'updated_at' => '2019-11-29 10:00:17',
                'shift_pattern_id' => 15,
                'day_seq' => 2,
                'day_type_id' => 2,
            ),
            66 => 
            array (
                'id' => 70,
                'created_at' => '2019-11-29 10:00:22',
                'updated_at' => '2019-11-29 10:00:22',
                'shift_pattern_id' => 15,
                'day_seq' => 3,
                'day_type_id' => 2,
            ),
            67 => 
            array (
                'id' => 71,
                'created_at' => '2019-11-29 10:00:27',
                'updated_at' => '2019-11-29 10:00:27',
                'shift_pattern_id' => 15,
                'day_seq' => 4,
                'day_type_id' => 2,
            ),
            68 => 
            array (
                'id' => 72,
                'created_at' => '2019-11-29 10:00:31',
                'updated_at' => '2019-11-29 10:00:31',
                'shift_pattern_id' => 15,
                'day_seq' => 5,
                'day_type_id' => 2,
            ),
            69 => 
            array (
                'id' => 73,
                'created_at' => '2019-11-29 10:00:35',
                'updated_at' => '2019-11-29 10:00:35',
                'shift_pattern_id' => 15,
                'day_seq' => 6,
                'day_type_id' => 2,
            ),
            70 => 
            array (
                'id' => 74,
                'created_at' => '2019-11-29 10:00:41',
                'updated_at' => '2019-11-29 10:00:41',
                'shift_pattern_id' => 15,
                'day_seq' => 7,
                'day_type_id' => 2,
            ),
            71 => 
            array (
                'id' => 75,
                'created_at' => '2019-11-29 10:00:45',
                'updated_at' => '2019-11-29 10:00:45',
                'shift_pattern_id' => 15,
                'day_seq' => 8,
                'day_type_id' => 2,
            ),
            72 => 
            array (
                'id' => 76,
                'created_at' => '2019-12-03 11:00:34',
                'updated_at' => '2019-12-03 11:00:34',
                'shift_pattern_id' => 3,
                'day_seq' => 8,
                'day_type_id' => 3,
            ),
            73 => 
            array (
                'id' => 77,
                'created_at' => '2019-12-03 11:01:11',
                'updated_at' => '2019-12-03 11:01:11',
                'shift_pattern_id' => 3,
                'day_seq' => 9,
                'day_type_id' => 2,
            ),
            74 => 
            array (
                'id' => 78,
                'created_at' => '2019-12-03 11:05:11',
                'updated_at' => '2019-12-03 11:05:11',
                'shift_pattern_id' => 15,
                'day_seq' => 9,
                'day_type_id' => 24,
            ),
            75 => 
            array (
                'id' => 79,
                'created_at' => '2019-12-03 11:05:22',
                'updated_at' => '2019-12-03 11:05:22',
                'shift_pattern_id' => 15,
                'day_seq' => 10,
                'day_type_id' => 20,
            ),
            76 => 
            array (
                'id' => 80,
                'created_at' => '2019-12-03 11:05:32',
                'updated_at' => '2019-12-03 11:05:32',
                'shift_pattern_id' => 15,
                'day_seq' => 11,
                'day_type_id' => 14,
            ),
            77 => 
            array (
                'id' => 81,
                'created_at' => '2019-12-03 11:08:45',
                'updated_at' => '2019-12-03 11:08:45',
                'shift_pattern_id' => 9,
                'day_seq' => 1,
                'day_type_id' => 12,
            ),
            78 => 
            array (
                'id' => 83,
                'created_at' => '2019-12-17 11:49:19',
                'updated_at' => '2019-12-17 11:49:19',
                'shift_pattern_id' => 17,
                'day_seq' => 1,
                'day_type_id' => 25,
            ),
            79 => 
            array (
                'id' => 84,
                'created_at' => '2019-12-17 11:49:43',
                'updated_at' => '2019-12-17 11:49:43',
                'shift_pattern_id' => 17,
                'day_seq' => 2,
                'day_type_id' => 25,
            ),
            80 => 
            array (
                'id' => 85,
                'created_at' => '2019-12-17 11:49:50',
                'updated_at' => '2019-12-17 11:49:50',
                'shift_pattern_id' => 17,
                'day_seq' => 3,
                'day_type_id' => 25,
            ),
            81 => 
            array (
                'id' => 86,
                'created_at' => '2019-12-17 11:49:58',
                'updated_at' => '2019-12-17 11:49:58',
                'shift_pattern_id' => 17,
                'day_seq' => 4,
                'day_type_id' => 25,
            ),
            82 => 
            array (
                'id' => 88,
                'created_at' => '2019-12-17 11:50:46',
                'updated_at' => '2019-12-17 11:50:46',
                'shift_pattern_id' => 17,
                'day_seq' => 5,
                'day_type_id' => 4,
            ),
            83 => 
            array (
                'id' => 89,
                'created_at' => '2019-12-17 11:50:52',
                'updated_at' => '2019-12-17 11:50:52',
                'shift_pattern_id' => 17,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            84 => 
            array (
                'id' => 90,
                'created_at' => '2019-12-17 11:50:57',
                'updated_at' => '2019-12-17 11:50:57',
                'shift_pattern_id' => 17,
                'day_seq' => 7,
                'day_type_id' => 4,
            ),
            85 => 
            array (
                'id' => 91,
                'created_at' => '2019-12-17 11:51:03',
                'updated_at' => '2019-12-17 11:51:03',
                'shift_pattern_id' => 17,
                'day_seq' => 8,
                'day_type_id' => 5,
            ),
            86 => 
            array (
                'id' => 92,
                'created_at' => '2019-12-17 11:52:43',
                'updated_at' => '2019-12-17 11:52:43',
                'shift_pattern_id' => 18,
                'day_seq' => 1,
                'day_type_id' => 26,
            ),
            87 => 
            array (
                'id' => 93,
                'created_at' => '2019-12-17 11:53:05',
                'updated_at' => '2019-12-17 11:53:05',
                'shift_pattern_id' => 18,
                'day_seq' => 2,
                'day_type_id' => 26,
            ),
            88 => 
            array (
                'id' => 94,
                'created_at' => '2019-12-17 11:53:10',
                'updated_at' => '2019-12-17 11:53:10',
                'shift_pattern_id' => 18,
                'day_seq' => 3,
                'day_type_id' => 26,
            ),
            89 => 
            array (
                'id' => 95,
                'created_at' => '2019-12-17 11:53:14',
                'updated_at' => '2019-12-17 11:53:14',
                'shift_pattern_id' => 18,
                'day_seq' => 4,
                'day_type_id' => 26,
            ),
            90 => 
            array (
                'id' => 96,
                'created_at' => '2019-12-17 11:53:19',
                'updated_at' => '2019-12-17 11:53:19',
                'shift_pattern_id' => 18,
                'day_seq' => 5,
                'day_type_id' => 4,
            ),
            91 => 
            array (
                'id' => 97,
                'created_at' => '2019-12-17 11:53:24',
                'updated_at' => '2019-12-17 11:53:24',
                'shift_pattern_id' => 18,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            92 => 
            array (
                'id' => 98,
                'created_at' => '2019-12-17 11:53:27',
                'updated_at' => '2019-12-17 11:53:27',
                'shift_pattern_id' => 18,
                'day_seq' => 7,
                'day_type_id' => 4,
            ),
            93 => 
            array (
                'id' => 99,
                'created_at' => '2019-12-17 11:53:32',
                'updated_at' => '2019-12-17 11:53:32',
                'shift_pattern_id' => 18,
                'day_seq' => 8,
                'day_type_id' => 5,
            ),
            94 => 
            array (
                'id' => 100,
                'created_at' => '2020-01-13 09:42:02',
                'updated_at' => '2020-01-13 09:42:02',
                'shift_pattern_id' => 19,
                'day_seq' => 1,
                'day_type_id' => 27,
            ),
            95 => 
            array (
                'id' => 101,
                'created_at' => '2020-01-14 11:32:04',
                'updated_at' => '2020-01-14 11:32:04',
                'shift_pattern_id' => 20,
                'day_seq' => 1,
                'day_type_id' => 12,
            ),
            96 => 
            array (
                'id' => 102,
                'created_at' => '2020-01-14 15:25:42',
                'updated_at' => '2020-01-14 15:25:42',
                'shift_pattern_id' => 24,
                'day_seq' => 1,
                'day_type_id' => 7,
            ),
            97 => 
            array (
                'id' => 103,
                'created_at' => '2020-01-14 15:32:00',
                'updated_at' => '2020-01-14 15:32:00',
                'shift_pattern_id' => 25,
                'day_seq' => 1,
                'day_type_id' => 7,
            ),
            98 => 
            array (
                'id' => 104,
                'created_at' => '2020-01-14 16:11:19',
                'updated_at' => '2020-01-14 16:11:19',
                'shift_pattern_id' => 26,
                'day_seq' => 1,
                'day_type_id' => 7,
            ),
            99 => 
            array (
                'id' => 105,
                'created_at' => '2020-01-14 17:05:05',
                'updated_at' => '2020-01-14 17:05:05',
                'shift_pattern_id' => 26,
                'day_seq' => 2,
                'day_type_id' => 7,
            ),
        ));
        
        
    }
}