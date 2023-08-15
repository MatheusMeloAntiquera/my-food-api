<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brazilId = DB::table('countries')->insertGetId(
            ['name' => 'Brazil', 'abbreviation' => 'BR']
        );

        $stateId = DB::table('states')->insertGetId(
            ['name' => 'São Paulo', 'abbreviation' => 'SP', 'country' => $brazilId]
        );

        DB::table('cities')->insertGetId(
            ['name' => 'São Paulo', 'state' => $stateId]
        );

        $stateId = DB::table('states')->insertGetId(
            ['name' => 'Rio de Janeiro', 'abbreviation' => 'RJ', 'country' => $brazilId]
        );

        DB::table('cities')->insertGetId(
            ['name' => 'Rio de Janeiro', 'state' => $stateId]
        );
    }
}
