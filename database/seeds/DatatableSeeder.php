<?php

use Illuminate\Database\Seeder;

class DatatableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 10)->create();
        factory(App\Post::class, 20)->create();
    }
}
