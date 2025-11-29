<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MHQUestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MhQuestion::insert([
            [
                'prompt' => 'On a scale of 1–5, how would you rate your mood today?',
                'type' => 'scale',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'prompt' => 'What has been the biggest source of stress lately?',
                'type' => 'text',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'prompt' => 'Do you have someone you feel comfortable talking to at EVSU?',
                'type' => 'single',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'prompt' => 'What coping strategies help you most?',
                'type' => 'text',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'prompt' => 'Would you like to be contacted by a counselor if needed?',
                'type' => 'single',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
