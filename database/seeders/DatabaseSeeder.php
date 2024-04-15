<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        try {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        } catch (\Exception $e) {
            // do nothing
            print_r($e->getMessage() . "\n");
        }

        // Conversation::factory(100)->create();
        // Message::factory(100)->create();
    }
}
