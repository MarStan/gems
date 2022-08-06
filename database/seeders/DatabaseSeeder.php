<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private array $employees = [
        ['token' => '7S$16U^FmxkdV!1b', 'email' => 'stephan@usergems.com'],
        ['token' => 'Ay@T3ZwF3YN^fZ@M', 'email' => 'christian@usergems.com'],
        ['token' => 'PK7UBPVeG%3pP9%B', 'email' => 'joss@usergems.com'],
        ['token' => 'c0R*4iQK21McwLww', 'email' => 'blaise@usergems.com'],
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert($this->employees);
    }
}
