<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
        	[
            'name'=> 'esportes',
        	'creator_id'=> User::first()->id
        	]);
        DB::table('categories')->insert(
            [
            'name'=> 'culinária',
            'creator_id'=> User::first()->id
            ]);
        DB::table('categories')->insert(
            [
            'name'=> 'saúde',
            'creator_id'=> User::first()->id
            ]);
        DB::table('categories')->insert(
            [
            'name'=> 'lazer',
            'creator_id'=> User::first()->id
            ]);
        DB::table('categories')->insert(
            [
            'name'=> 'moda',
            'creator_id'=> User::first()->id
            ]);
        DB::table('categories')->insert(
            [
            'name'=> 'comportamento',
            'creator_id'=> User::first()->id
            ]);
    }
}
