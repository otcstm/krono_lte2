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
                'created_at' => '2020-04-09 17:32:48',
                'updated_at' => '2020-04-09 17:32:48',
                'shift_pattern_id' => 5912,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            1 => 
            array (
                'id' => 2,
                'created_at' => '2020-04-09 17:33:02',
                'updated_at' => '2020-04-09 17:33:02',
                'shift_pattern_id' => 5912,
                'day_seq' => 2,
                'day_type_id' => 2,
            ),
            2 => 
            array (
                'id' => 3,
                'created_at' => '2020-04-09 17:33:06',
                'updated_at' => '2020-04-09 17:33:06',
                'shift_pattern_id' => 5912,
                'day_seq' => 3,
                'day_type_id' => 2,
            ),
            3 => 
            array (
                'id' => 4,
                'created_at' => '2020-04-09 17:33:09',
                'updated_at' => '2020-04-09 17:33:09',
                'shift_pattern_id' => 5912,
                'day_seq' => 4,
                'day_type_id' => 2,
            ),
            4 => 
            array (
                'id' => 5,
                'created_at' => '2020-04-09 17:33:21',
                'updated_at' => '2020-04-09 17:33:21',
                'shift_pattern_id' => 5912,
                'day_seq' => 5,
                'day_type_id' => 3,
            ),
            5 => 
            array (
                'id' => 6,
                'created_at' => '2020-04-09 17:33:29',
                'updated_at' => '2020-04-09 17:33:29',
                'shift_pattern_id' => 5912,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            6 => 
            array (
                'id' => 7,
                'created_at' => '2020-04-09 17:33:35',
                'updated_at' => '2020-04-09 17:33:35',
                'shift_pattern_id' => 5912,
                'day_seq' => 7,
                'day_type_id' => 5,
            ),
            7 => 
            array (
                'id' => 9,
                'created_at' => '2020-04-09 23:43:17',
                'updated_at' => '2020-04-09 23:43:17',
                'shift_pattern_id' => 5914,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            8 => 
            array (
                'id' => 10,
                'created_at' => '2020-04-09 23:43:55',
                'updated_at' => '2020-04-09 23:43:55',
                'shift_pattern_id' => 5914,
                'day_seq' => 2,
                'day_type_id' => 2,
            ),
            9 => 
            array (
                'id' => 11,
                'created_at' => '2020-04-09 23:44:32',
                'updated_at' => '2020-04-09 23:44:32',
                'shift_pattern_id' => 5914,
                'day_seq' => 3,
                'day_type_id' => 2,
            ),
            10 => 
            array (
                'id' => 12,
                'created_at' => '2020-04-09 23:45:28',
                'updated_at' => '2020-04-09 23:45:28',
                'shift_pattern_id' => 5914,
                'day_seq' => 4,
                'day_type_id' => 6,
            ),
            11 => 
            array (
                'id' => 13,
                'created_at' => '2020-04-09 23:46:25',
                'updated_at' => '2020-04-09 23:46:25',
                'shift_pattern_id' => 5914,
                'day_seq' => 5,
                'day_type_id' => 5,
            ),
            12 => 
            array (
                'id' => 14,
                'created_at' => '2020-04-09 23:46:50',
                'updated_at' => '2020-04-09 23:46:50',
                'shift_pattern_id' => 5914,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            13 => 
            array (
                'id' => 15,
                'created_at' => '2020-04-09 23:47:09',
                'updated_at' => '2020-04-09 23:47:09',
                'shift_pattern_id' => 5914,
                'day_seq' => 7,
                'day_type_id' => 2,
            ),
            14 => 
            array (
                'id' => 16,
                'created_at' => '2020-04-09 23:48:40',
                'updated_at' => '2020-04-09 23:48:40',
                'shift_pattern_id' => 5915,
                'day_seq' => 1,
                'day_type_id' => 7,
            ),
            15 => 
            array (
                'id' => 17,
                'created_at' => '2020-04-09 23:49:03',
                'updated_at' => '2020-04-09 23:49:03',
                'shift_pattern_id' => 5915,
                'day_seq' => 2,
                'day_type_id' => 7,
            ),
            16 => 
            array (
                'id' => 18,
                'created_at' => '2020-04-09 23:49:29',
                'updated_at' => '2020-04-09 23:49:29',
                'shift_pattern_id' => 5915,
                'day_seq' => 3,
                'day_type_id' => 7,
            ),
            17 => 
            array (
                'id' => 19,
                'created_at' => '2020-04-09 23:50:22',
                'updated_at' => '2020-04-09 23:50:22',
                'shift_pattern_id' => 5915,
                'day_seq' => 4,
                'day_type_id' => 7,
            ),
            18 => 
            array (
                'id' => 20,
                'created_at' => '2020-04-09 23:50:53',
                'updated_at' => '2020-04-09 23:50:53',
                'shift_pattern_id' => 5915,
                'day_seq' => 5,
                'day_type_id' => 3,
            ),
            19 => 
            array (
                'id' => 21,
                'created_at' => '2020-04-09 23:52:11',
                'updated_at' => '2020-04-09 23:52:11',
                'shift_pattern_id' => 5915,
                'day_seq' => 6,
                'day_type_id' => 11,
            ),
            20 => 
            array (
                'id' => 22,
                'created_at' => '2020-04-09 23:52:38',
                'updated_at' => '2020-04-09 23:52:38',
                'shift_pattern_id' => 5915,
                'day_seq' => 7,
                'day_type_id' => 5,
            ),
            21 => 
            array (
                'id' => 30,
                'created_at' => '2020-04-24 12:42:18',
                'updated_at' => '2020-04-24 12:42:18',
                'shift_pattern_id' => 7027,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            22 => 
            array (
                'id' => 31,
                'created_at' => '2020-04-24 12:42:49',
                'updated_at' => '2020-04-24 12:42:49',
                'shift_pattern_id' => 7027,
                'day_seq' => 2,
                'day_type_id' => 19,
            ),
            23 => 
            array (
                'id' => 32,
                'created_at' => '2020-04-24 12:43:08',
                'updated_at' => '2020-04-24 12:43:08',
                'shift_pattern_id' => 7027,
                'day_seq' => 3,
                'day_type_id' => 4,
            ),
            24 => 
            array (
                'id' => 33,
                'created_at' => '2020-04-24 12:43:20',
                'updated_at' => '2020-04-24 12:43:20',
                'shift_pattern_id' => 7027,
                'day_seq' => 4,
                'day_type_id' => 5,
            ),
            25 => 
            array (
                'id' => 34,
                'created_at' => '2020-04-25 04:31:41',
                'updated_at' => '2020-04-25 04:31:41',
                'shift_pattern_id' => 7047,
                'day_seq' => 1,
                'day_type_id' => 20,
            ),
            26 => 
            array (
                'id' => 35,
                'created_at' => '2020-04-25 04:31:48',
                'updated_at' => '2020-04-25 04:31:48',
                'shift_pattern_id' => 7047,
                'day_seq' => 2,
                'day_type_id' => 20,
            ),
            27 => 
            array (
                'id' => 37,
                'created_at' => '2020-04-25 04:32:08',
                'updated_at' => '2020-04-25 04:32:08',
                'shift_pattern_id' => 7047,
                'day_seq' => 3,
                'day_type_id' => 20,
            ),
            28 => 
            array (
                'id' => 38,
                'created_at' => '2020-04-25 04:32:21',
                'updated_at' => '2020-04-25 04:32:21',
                'shift_pattern_id' => 7047,
                'day_seq' => 4,
                'day_type_id' => 20,
            ),
            29 => 
            array (
                'id' => 42,
                'created_at' => '2020-04-25 09:14:07',
                'updated_at' => '2020-04-25 09:14:07',
                'shift_pattern_id' => 7050,
                'day_seq' => 1,
                'day_type_id' => 25,
            ),
            30 => 
            array (
                'id' => 43,
                'created_at' => '2020-04-25 09:14:24',
                'updated_at' => '2020-04-25 09:14:24',
                'shift_pattern_id' => 7050,
                'day_seq' => 2,
                'day_type_id' => 25,
            ),
            31 => 
            array (
                'id' => 44,
                'created_at' => '2020-04-25 09:14:40',
                'updated_at' => '2020-04-25 09:14:40',
                'shift_pattern_id' => 7050,
                'day_seq' => 3,
                'day_type_id' => 25,
            ),
            32 => 
            array (
                'id' => 45,
                'created_at' => '2020-04-25 09:14:48',
                'updated_at' => '2020-04-25 09:14:48',
                'shift_pattern_id' => 7050,
                'day_seq' => 4,
                'day_type_id' => 25,
            ),
            33 => 
            array (
                'id' => 46,
                'created_at' => '2020-04-25 09:14:55',
                'updated_at' => '2020-04-25 09:14:55',
                'shift_pattern_id' => 7050,
                'day_seq' => 5,
                'day_type_id' => 25,
            ),
            34 => 
            array (
                'id' => 47,
                'created_at' => '2020-04-25 09:16:38',
                'updated_at' => '2020-04-25 09:16:38',
                'shift_pattern_id' => 7050,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            35 => 
            array (
                'id' => 48,
                'created_at' => '2020-04-25 09:17:14',
                'updated_at' => '2020-04-25 09:17:14',
                'shift_pattern_id' => 7050,
                'day_seq' => 7,
                'day_type_id' => 5,
            ),
            36 => 
            array (
                'id' => 49,
                'created_at' => '2020-04-25 09:20:40',
                'updated_at' => '2020-04-25 09:20:40',
                'shift_pattern_id' => 7049,
                'day_seq' => 1,
                'day_type_id' => 24,
            ),
            37 => 
            array (
                'id' => 50,
                'created_at' => '2020-04-25 09:20:47',
                'updated_at' => '2020-04-25 09:20:47',
                'shift_pattern_id' => 7049,
                'day_seq' => 2,
                'day_type_id' => 24,
            ),
            38 => 
            array (
                'id' => 51,
                'created_at' => '2020-04-25 09:21:07',
                'updated_at' => '2020-04-25 09:21:07',
                'shift_pattern_id' => 7049,
                'day_seq' => 3,
                'day_type_id' => 24,
            ),
            39 => 
            array (
                'id' => 52,
                'created_at' => '2020-04-25 09:21:19',
                'updated_at' => '2020-04-25 09:21:19',
                'shift_pattern_id' => 7049,
                'day_seq' => 4,
                'day_type_id' => 24,
            ),
            40 => 
            array (
                'id' => 53,
                'created_at' => '2020-04-25 09:21:26',
                'updated_at' => '2020-04-25 09:21:26',
                'shift_pattern_id' => 7049,
                'day_seq' => 5,
                'day_type_id' => 24,
            ),
            41 => 
            array (
                'id' => 54,
                'created_at' => '2020-04-25 09:21:33',
                'updated_at' => '2020-04-25 09:21:33',
                'shift_pattern_id' => 7049,
                'day_seq' => 6,
                'day_type_id' => 4,
            ),
            42 => 
            array (
                'id' => 55,
                'created_at' => '2020-04-25 09:21:45',
                'updated_at' => '2020-04-25 09:21:45',
                'shift_pattern_id' => 7049,
                'day_seq' => 7,
                'day_type_id' => 5,
            ),
            43 => 
            array (
                'id' => 57,
                'created_at' => '2020-05-03 09:12:54',
                'updated_at' => '2020-05-03 09:12:54',
                'shift_pattern_id' => 7052,
                'day_seq' => 1,
                'day_type_id' => 26,
            ),
            44 => 
            array (
                'id' => 58,
                'created_at' => '2020-05-03 09:13:09',
                'updated_at' => '2020-05-03 09:13:09',
                'shift_pattern_id' => 7052,
                'day_seq' => 2,
                'day_type_id' => 26,
            ),
            45 => 
            array (
                'id' => 59,
                'created_at' => '2020-05-03 09:13:18',
                'updated_at' => '2020-05-03 09:13:18',
                'shift_pattern_id' => 7052,
                'day_seq' => 3,
                'day_type_id' => 26,
            ),
            46 => 
            array (
                'id' => 60,
                'created_at' => '2020-05-03 09:13:26',
                'updated_at' => '2020-05-03 09:13:26',
                'shift_pattern_id' => 7052,
                'day_seq' => 4,
                'day_type_id' => 26,
            ),
            47 => 
            array (
                'id' => 61,
                'created_at' => '2020-05-03 09:13:36',
                'updated_at' => '2020-05-03 09:13:36',
                'shift_pattern_id' => 7052,
                'day_seq' => 5,
                'day_type_id' => 26,
            ),
            48 => 
            array (
                'id' => 62,
                'created_at' => '2020-05-03 09:13:45',
                'updated_at' => '2020-05-03 09:13:45',
                'shift_pattern_id' => 7052,
                'day_seq' => 6,
                'day_type_id' => 26,
            ),
            49 => 
            array (
                'id' => 63,
                'created_at' => '2020-05-03 09:13:57',
                'updated_at' => '2020-05-03 09:13:57',
                'shift_pattern_id' => 7052,
                'day_seq' => 7,
                'day_type_id' => 26,
            ),
            50 => 
            array (
                'id' => 64,
                'created_at' => '2020-05-04 11:27:58',
                'updated_at' => '2020-05-04 11:27:58',
                'shift_pattern_id' => 7053,
                'day_seq' => 1,
                'day_type_id' => 28,
            ),
            51 => 
            array (
                'id' => 65,
                'created_at' => '2020-05-04 11:28:15',
                'updated_at' => '2020-05-04 11:28:15',
                'shift_pattern_id' => 7053,
                'day_seq' => 2,
                'day_type_id' => 28,
            ),
            52 => 
            array (
                'id' => 66,
                'created_at' => '2020-05-04 11:28:49',
                'updated_at' => '2020-05-04 11:28:49',
                'shift_pattern_id' => 7053,
                'day_seq' => 3,
                'day_type_id' => 28,
            ),
            53 => 
            array (
                'id' => 67,
                'created_at' => '2020-05-04 11:29:37',
                'updated_at' => '2020-05-04 11:29:37',
                'shift_pattern_id' => 7053,
                'day_seq' => 4,
                'day_type_id' => 28,
            ),
            54 => 
            array (
                'id' => 68,
                'created_at' => '2020-05-04 11:29:54',
                'updated_at' => '2020-05-04 11:29:54',
                'shift_pattern_id' => 7053,
                'day_seq' => 5,
                'day_type_id' => 28,
            ),
            55 => 
            array (
                'id' => 69,
                'created_at' => '2020-05-04 11:32:26',
                'updated_at' => '2020-05-04 11:32:26',
                'shift_pattern_id' => 7053,
                'day_seq' => 6,
                'day_type_id' => 28,
            ),
            56 => 
            array (
                'id' => 70,
                'created_at' => '2020-05-04 11:32:42',
                'updated_at' => '2020-05-04 11:32:42',
                'shift_pattern_id' => 7053,
                'day_seq' => 7,
                'day_type_id' => 4,
            ),
            57 => 
            array (
                'id' => 71,
                'created_at' => '2020-05-04 11:32:54',
                'updated_at' => '2020-05-04 11:32:54',
                'shift_pattern_id' => 7053,
                'day_seq' => 8,
                'day_type_id' => 5,
            ),
            58 => 
            array (
                'id' => 72,
                'created_at' => '2020-05-04 11:34:51',
                'updated_at' => '2020-05-04 11:34:51',
                'shift_pattern_id' => 7055,
                'day_seq' => 1,
                'day_type_id' => 30,
            ),
            59 => 
            array (
                'id' => 73,
                'created_at' => '2020-05-04 11:35:07',
                'updated_at' => '2020-05-04 11:35:07',
                'shift_pattern_id' => 7055,
                'day_seq' => 2,
                'day_type_id' => 30,
            ),
            60 => 
            array (
                'id' => 74,
                'created_at' => '2020-05-04 11:35:16',
                'updated_at' => '2020-05-04 11:35:16',
                'shift_pattern_id' => 7055,
                'day_seq' => 3,
                'day_type_id' => 30,
            ),
            61 => 
            array (
                'id' => 75,
                'created_at' => '2020-05-04 11:35:24',
                'updated_at' => '2020-05-04 11:35:24',
                'shift_pattern_id' => 7055,
                'day_seq' => 4,
                'day_type_id' => 30,
            ),
            62 => 
            array (
                'id' => 76,
                'created_at' => '2020-05-04 11:35:37',
                'updated_at' => '2020-05-04 11:35:37',
                'shift_pattern_id' => 7055,
                'day_seq' => 5,
                'day_type_id' => 30,
            ),
            63 => 
            array (
                'id' => 77,
                'created_at' => '2020-05-04 11:35:48',
                'updated_at' => '2020-05-04 11:35:48',
                'shift_pattern_id' => 7055,
                'day_seq' => 6,
                'day_type_id' => 30,
            ),
            64 => 
            array (
                'id' => 79,
                'created_at' => '2020-05-04 11:36:21',
                'updated_at' => '2020-05-04 11:36:21',
                'shift_pattern_id' => 7055,
                'day_seq' => 7,
                'day_type_id' => 4,
            ),
            65 => 
            array (
                'id' => 80,
                'created_at' => '2020-05-04 11:36:33',
                'updated_at' => '2020-05-04 11:36:33',
                'shift_pattern_id' => 7055,
                'day_seq' => 8,
                'day_type_id' => 5,
            ),
            66 => 
            array (
                'id' => 81,
                'created_at' => '2020-05-04 11:44:58',
                'updated_at' => '2020-05-04 11:44:58',
                'shift_pattern_id' => 7054,
                'day_seq' => 1,
                'day_type_id' => 29,
            ),
            67 => 
            array (
                'id' => 82,
                'created_at' => '2020-05-04 11:45:07',
                'updated_at' => '2020-05-04 11:45:07',
                'shift_pattern_id' => 7054,
                'day_seq' => 2,
                'day_type_id' => 29,
            ),
            68 => 
            array (
                'id' => 83,
                'created_at' => '2020-05-04 11:45:17',
                'updated_at' => '2020-05-04 11:45:17',
                'shift_pattern_id' => 7054,
                'day_seq' => 3,
                'day_type_id' => 29,
            ),
            69 => 
            array (
                'id' => 84,
                'created_at' => '2020-05-04 11:45:39',
                'updated_at' => '2020-05-04 11:45:39',
                'shift_pattern_id' => 7054,
                'day_seq' => 4,
                'day_type_id' => 29,
            ),
            70 => 
            array (
                'id' => 85,
                'created_at' => '2020-05-04 11:45:54',
                'updated_at' => '2020-05-04 11:45:54',
                'shift_pattern_id' => 7054,
                'day_seq' => 5,
                'day_type_id' => 29,
            ),
            71 => 
            array (
                'id' => 86,
                'created_at' => '2020-05-04 11:46:07',
                'updated_at' => '2020-05-04 11:46:07',
                'shift_pattern_id' => 7054,
                'day_seq' => 6,
                'day_type_id' => 29,
            ),
            72 => 
            array (
                'id' => 87,
                'created_at' => '2020-05-04 11:46:17',
                'updated_at' => '2020-05-04 11:46:17',
                'shift_pattern_id' => 7054,
                'day_seq' => 7,
                'day_type_id' => 4,
            ),
            73 => 
            array (
                'id' => 88,
                'created_at' => '2020-05-04 11:46:25',
                'updated_at' => '2020-05-04 11:46:25',
                'shift_pattern_id' => 7054,
                'day_seq' => 8,
                'day_type_id' => 5,
            ),
            74 => 
            array (
                'id' => 89,
                'created_at' => '2020-05-05 05:47:34',
                'updated_at' => '2020-05-05 05:47:34',
                'shift_pattern_id' => 7047,
                'day_seq' => 5,
                'day_type_id' => 21,
            ),
            75 => 
            array (
                'id' => 90,
                'created_at' => '2020-05-05 05:47:52',
                'updated_at' => '2020-05-05 05:47:52',
                'shift_pattern_id' => 7047,
                'day_seq' => 6,
                'day_type_id' => 22,
            ),
            76 => 
            array (
                'id' => 91,
                'created_at' => '2020-05-05 05:48:15',
                'updated_at' => '2020-05-05 05:48:15',
                'shift_pattern_id' => 7047,
                'day_seq' => 7,
                'day_type_id' => 20,
            ),
            77 => 
            array (
                'id' => 92,
                'created_at' => '2020-05-05 05:48:28',
                'updated_at' => '2020-05-05 05:48:28',
                'shift_pattern_id' => 7047,
                'day_seq' => 8,
                'day_type_id' => 20,
            ),
            78 => 
            array (
                'id' => 93,
                'created_at' => '2020-05-14 23:19:31',
                'updated_at' => '2020-05-14 23:19:31',
                'shift_pattern_id' => 7056,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            79 => 
            array (
                'id' => 94,
                'created_at' => '2020-05-14 23:21:59',
                'updated_at' => '2020-05-14 23:21:59',
                'shift_pattern_id' => 7056,
                'day_seq' => 2,
                'day_type_id' => 34,
            ),
            80 => 
            array (
                'id' => 95,
                'created_at' => '2020-05-18 09:21:43',
                'updated_at' => '2020-05-18 09:21:43',
                'shift_pattern_id' => 7057,
                'day_seq' => 1,
                'day_type_id' => 36,
            ),
            81 => 
            array (
                'id' => 96,
                'created_at' => '2020-05-18 09:32:20',
                'updated_at' => '2020-05-18 09:32:20',
                'shift_pattern_id' => 7058,
                'day_seq' => 1,
                'day_type_id' => 2,
            ),
            82 => 
            array (
                'id' => 97,
                'created_at' => '2020-05-18 09:32:51',
                'updated_at' => '2020-05-18 09:32:51',
                'shift_pattern_id' => 7058,
                'day_seq' => 2,
                'day_type_id' => 6,
            ),
        ));
        
        
    }
}