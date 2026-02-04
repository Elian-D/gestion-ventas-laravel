<?php

namespace Database\Seeders\ClientsConfigSeeders;

use App\Models\Clients\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::factory()->count(30)->create();
    }

}
