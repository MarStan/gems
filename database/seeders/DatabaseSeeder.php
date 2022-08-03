<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private array $calendarApiData = [
        ['token' => '7S$16U^FmxkdV!1b', 'email' => 'stephan@usergems.com', 'is_usergems_employee' => true],
        ['token' => 'Ay@T3ZwF3YN^fZ@M', 'email' => 'christian@usergems.com', 'is_usergems_employee' => true],
        ['token' => 'PK7UBPVeG%3pP9%B', 'email' => 'joss@usergems.com', 'is_usergems_employee' => true],
        ['token' => 'c0R*4iQK21McwLww', 'email' => 'blaise@usergems.com', 'is_usergems_employee' => true],
//        ['token' => null, 'email' => 'demi@algolia.com', 'is_usergems_employee' => false],
//        ['token' => null, 'email' => 'joshua@algolia.com', 'is_usergems_employee' => false],
//        ['token' => null, 'email' => 'woojin@algolia.com', 'is_usergems_employee' => false],
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('calendar_users')->insert($this->calendarApiData);
    }
}
