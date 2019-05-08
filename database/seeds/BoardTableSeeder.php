<?php

use App\Models\Board;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $firstBoard = new Board();
        $firstBoard->name = '综合版';
        $firstBoard->introduction = '第一个板块';
        $firstBoard->save();
    }
}
