<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("genders")->insert([
            "gender" => "male",
        ]);
    }
}
