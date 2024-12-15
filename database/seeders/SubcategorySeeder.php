<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mens_boys = DB::table('categories')->where('slug', "men's-&-boys'-fashion")->value('id');
        $women_girl = DB::table('categories')->where('slug', "women's-&-girls'-fashion")->value('id');
        $electronic_acc = DB::table('categories')->where('slug', "electronic-accessories")->value('id');
        $home_decor = DB::table('categories')->where('slug', 'home-decor')->value('id');
        $women_cloth = DB::table('categories')->where('slug', 'women-clothing')->value('id');
        $electronics = DB::table('categories')->where('slug', 'electronics-device')->value('id');
        $groceries = DB::table('categories')->where('slug', 'groceries')->value('id');
        $kitchen_home = DB::table('categories')->where('slug', "kitchen-&-home-appliances")->value('id');

        // Generate and insert subcategories
        $mens_boys_subs = [
            ['name' => 'Subcategory 1', 'slug' => 'subcategory-1', 'category_id' => $mens_boys, 'status' => 1],
            ['name' => 'Subcategory 2', 'slug' => 'subcategory-2', 'category_id' => $mens_boys, 'status' => 1],
            ['name' => 'Subcategory 3', 'slug' => 'subcategory-3', 'category_id' => $mens_boys, 'status' => 1],
            ['name' => 'Subcategory 4', 'slug' => 'subcategory-4', 'category_id' => $mens_boys, 'status' => 1],
            ['name' => 'Subcategory 5', 'slug' => 'subcategory-5', 'category_id' => $mens_boys, 'status' => 1],
            ['name' => 'Subcategory 6', 'slug' => 'subcategory-6', 'category_id' => $mens_boys, 'status' => 1],
            ['name' => 'Subcategory 7', 'slug' => 'subcategory-7', 'category_id' => $mens_boys, 'status' => 1],
        ];
        $women_girl_subs = [
            ['name' => 'Subcategory 1', 'slug' => 'subcategory-1', 'category_id' => $women_girl, 'status' => 1],
            ['name' => 'Subcategory 2', 'slug' => 'subcategory-2', 'category_id' => $women_girl, 'status' => 1],
            ['name' => 'Subcategory 3', 'slug' => 'subcategory-3', 'category_id' => $women_girl, 'status' => 1],
            ['name' => 'Subcategory 4', 'slug' => 'subcategory-4', 'category_id' => $women_girl, 'status' => 1],
            ['name' => 'Subcategory 5', 'slug' => 'subcategory-5', 'category_id' => $women_girl, 'status' => 1],
            ['name' => 'Subcategory 6', 'slug' => 'subcategory-6', 'category_id' => $women_girl, 'status' => 1],
            ['name' => 'Subcategory 7', 'slug' => 'subcategory-7', 'category_id' => $women_girl, 'status' => 1],
        ];
        $electronic_acc_subs = [
            ['name' => 'Subcategory 1', 'slug' => 'subcategory-1', 'category_id' => $electronic_acc, 'status' => 1],
            ['name' => 'Subcategory 2', 'slug' => 'subcategory-2', 'category_id' => $electronic_acc, 'status' => 1],
            ['name' => 'Subcategory 3', 'slug' => 'subcategory-3', 'category_id' => $electronic_acc, 'status' => 1],
            ['name' => 'Subcategory 4', 'slug' => 'subcategory-4', 'category_id' => $electronic_acc, 'status' => 1],
            ['name' => 'Subcategory 5', 'slug' => 'subcategory-5', 'category_id' => $electronic_acc, 'status' => 1],
            ['name' => 'Subcategory 6', 'slug' => 'subcategory-6', 'category_id' => $electronic_acc, 'status' => 1],
            ['name' => 'Subcategory 7', 'slug' => 'subcategory-7', 'category_id' => $electronic_acc, 'status' => 1],
        ];
        $home_decor_subs = [
            ['name' => 'Subcategory 1', 'slug' => 'subcategory-1', 'category_id' => $home_decor, 'status' => 1],
            ['name' => 'Subcategory 2', 'slug' => 'subcategory-2', 'category_id' => $home_decor, 'status' => 1],
            ['name' => 'Subcategory 3', 'slug' => 'subcategory-3', 'category_id' => $home_decor, 'status' => 1],
            ['name' => 'Subcategory 4', 'slug' => 'subcategory-4', 'category_id' => $home_decor, 'status' => 1],
            ['name' => 'Subcategory 5', 'slug' => 'subcategory-5', 'category_id' => $home_decor, 'status' => 1],
            ['name' => 'Subcategory 6', 'slug' => 'subcategory-6', 'category_id' => $home_decor, 'status' => 1],
            ['name' => 'Subcategory 7', 'slug' => 'subcategory-7', 'category_id' => $home_decor, 'status' => 1],
        ];
        $women_cloth_subs = [
            ['name' => 'Subcategory 1', 'slug' => 'subcategory-1', 'category_id' => $women_cloth, 'status' => 1],
            ['name' => 'Subcategory 2', 'slug' => 'subcategory-2', 'category_id' => $women_cloth, 'status' => 1],
            ['name' => 'Subcategory 3', 'slug' => 'subcategory-3', 'category_id' => $women_cloth, 'status' => 1],
            ['name' => 'Subcategory 4', 'slug' => 'subcategory-4', 'category_id' => $women_cloth, 'status' => 1],
            ['name' => 'Subcategory 5', 'slug' => 'subcategory-5', 'category_id' => $women_cloth, 'status' => 1],
            ['name' => 'Subcategory 6', 'slug' => 'subcategory-6', 'category_id' => $women_cloth, 'status' => 1],
            ['name' => 'Subcategory 7', 'slug' => 'subcategory-7', 'category_id' => $women_cloth, 'status' => 1],
        ];
        $electronics_subs = [
            ['name' => 'Subcategory 1', 'slug' => 'subcategory-1', 'category_id' => $electronics, 'status' => 1],
            ['name' => 'Subcategory 2', 'slug' => 'subcategory-2', 'category_id' => $electronics, 'status' => 1],
            ['name' => 'Subcategory 3', 'slug' => 'subcategory-3', 'category_id' => $electronics, 'status' => 1],
            ['name' => 'Subcategory 4', 'slug' => 'subcategory-4', 'category_id' => $electronics, 'status' => 1],
            ['name' => 'Subcategory 5', 'slug' => 'subcategory-5', 'category_id' => $electronics, 'status' => 1],
            ['name' => 'Subcategory 6', 'slug' => 'subcategory-6', 'category_id' => $electronics, 'status' => 1],
            ['name' => 'Subcategory 7', 'slug' => 'subcategory-7', 'category_id' => $electronics, 'status' => 1],
        ];
        $groceries_subs = [
            ['name' => 'Subcategory 1', 'slug' => 'subcategory-1', 'category_id' => $groceries, 'status' => 1],
            ['name' => 'Subcategory 2', 'slug' => 'subcategory-2', 'category_id' => $groceries, 'status' => 1],
            ['name' => 'Subcategory 3', 'slug' => 'subcategory-3', 'category_id' => $groceries, 'status' => 1],
            ['name' => 'Subcategory 4', 'slug' => 'subcategory-4', 'category_id' => $groceries, 'status' => 1],
            ['name' => 'Subcategory 5', 'slug' => 'subcategory-5', 'category_id' => $groceries, 'status' => 1],
            ['name' => 'Subcategory 6', 'slug' => 'subcategory-6', 'category_id' => $groceries, 'status' => 1],
            ['name' => 'Subcategory 7', 'slug' => 'subcategory-7', 'category_id' => $groceries, 'status' => 1],
        ];
        $kitchen_home_subs = [
            ['name' => 'Subcategory 1', 'slug' => 'subcategory-1', 'category_id' => $kitchen_home, 'status' => 1],
            ['name' => 'Subcategory 2', 'slug' => 'subcategory-2', 'category_id' => $kitchen_home, 'status' => 1],
            ['name' => 'Subcategory 3', 'slug' => 'subcategory-3', 'category_id' => $kitchen_home, 'status' => 1],
            ['name' => 'Subcategory 4', 'slug' => 'subcategory-4', 'category_id' => $kitchen_home, 'status' => 1],
            ['name' => 'Subcategory 5', 'slug' => 'subcategory-5', 'category_id' => $kitchen_home, 'status' => 1],
            ['name' => 'Subcategory 6', 'slug' => 'subcategory-6', 'category_id' => $kitchen_home, 'status' => 1],
            ['name' => 'Subcategory 7', 'slug' => 'subcategory-7', 'category_id' => $kitchen_home, 'status' => 1],
        ];

        Subcategory::insert($mens_boys_subs);
        Subcategory::insert($women_girl_subs);
        Subcategory::insert($electronic_acc_subs);
        Subcategory::insert($home_decor_subs);
        Subcategory::insert($women_cloth_subs);
        Subcategory::insert($electronics_subs);
        Subcategory::insert($groceries_subs);
        Subcategory::insert($kitchen_home_subs);
    }
}
