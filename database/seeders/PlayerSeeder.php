<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        DB::table('players')->insert([
            'team_id' => 1,
            'number' => 0,
            'name' => 'Enemy player',
            'position' => 'ENM',
        ]);

        $faker = Faker::create();

        for ( $i=0; $i<12; $i++ ) {
            DB::table('players')->insert([
                'team_id' => 2,
                'number' => $faker->numberBetween(1,99),
                'name' => $faker->name,
                'position' => 'POS',
            ]);
        }
    }
}
