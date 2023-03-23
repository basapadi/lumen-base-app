<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductUnit;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = ['batang', 'piece','lusin','dus'];

        ProductUnit::truncate();

        foreach ($units as $key => $unit) {
           ProductUnit::create([
            'name' => $unit,
            'description' => 'Dummy Description'
           ]);
        }

    }
}
