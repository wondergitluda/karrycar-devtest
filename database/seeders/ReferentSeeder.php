<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Referent::factory()->count(150)->create();

        for ($x = 0; $x < 1000; $x++) {
            $referent = \App\Models\Referent::query()->inRandomOrder()->first();
            for ($i = 0; $i < random_int(10, 20); $i++) {
                $duplicatedReferent = $referent->replicate();
                $duplicatedReferent->save();
                // \App\Models\Referent::factory()->create();
                DB::table('referent_shipment')->insert([
                    'referent_id' => $duplicatedReferent->id,
                    'shipment_id' => random_int(1, 1000),
                    'scope' => random_int(0, 1) !== 0 ? 'start' : 'end',
                ]);
            }
        }
    }
}
