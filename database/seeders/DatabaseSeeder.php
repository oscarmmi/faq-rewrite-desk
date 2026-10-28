<?php

namespace Database\Seeders;

use App\Models\FaqExample;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->updateOrCreate(['email' => 'test@example.com'], [
            'name' => 'Test User',
            'password' => 'password',
        ]);

        foreach ([
            [
                'title' => 'Track an order',
                'source' => 'Where is my order? Order HD-1001 shipped yesterday and should arrive within 3 business days. You can email help@harbordesk.example for an update.',
            ],
            [
                'title' => 'Request a return',
                'source' => 'You can return unused items within 30 days of delivery. Email returns@harbordesk.example with your order number to receive a prepaid label.',
            ],
            [
                'title' => 'Shipping costs',
                'source' => 'Standard shipping costs $4.95 and usually takes 5 business days. Orders over $50 ship free. Contact shipping@harbordesk.example if your package is delayed.',
            ],
        ] as $example) {
            FaqExample::query()->updateOrCreate(['title' => $example['title']], $example);
        }
    }
}
