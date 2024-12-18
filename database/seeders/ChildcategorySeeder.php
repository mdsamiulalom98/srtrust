<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subcategory;
use App\Models\Childcategory;
use Illumiante\Support\Facades\DB;

class ChildcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = Subcategory::all();

        foreach ($subcategories as $subcategory) {
            for ($i = 1; $i <= 2; $i++) { 
                $childcategory = Childcategory::create([
                    'name' => 'Childcategory ' . $i . ' of ' . $subcategory->name,
                    'name_bn' => 'Childcategory ' . $i . ' of ' . $subcategory->name,
                    'subcategory_id' => $subcategory->id,
                    'slug' => strtolower('childcategory-' . $i . '-' . $subcategory->id),
                    'status' => 1
                ]);
            }
        }
    }
}
