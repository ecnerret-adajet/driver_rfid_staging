<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(ClasificationTableSeeder::class);
        $this->call(ServerTableSeeder::class);
        $this->call(UserRoleTableSeeder::class);
    }
}
