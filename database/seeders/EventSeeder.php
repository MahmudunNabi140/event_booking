<?php

namespace Database\Seeders;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->datas() as $key => $value) {
            Event::create($value);
        }
    }

    private function datas()
    {
        return [
            [
            'name' => 'Music Concert',
            'description' => 'An amazing live music concert featuring popular bands.',
            'date' => '2024-12-15',
            'time' => '19:00:00',
            'location' => 'Madison Square Garden, New York',
            'available_seats' => 500,
            'price' => 49.99,
            'status' => 'Active',
            'created_at' => now(),
            ],
            [
            'name' => 'Tech Conference 2024',
            'description' => 'Join us for a cutting-edge tech conference with industry leaders.',
            'date' => '2024-11-20',
            'time' => '09:00:00',
            'location' => 'Silicon Valley Convention Center, California',
            'available_seats' => 200,
            'price' => 199.99,
            'status' => 'Active',
                'created_at' => now(),
            ],
            [
            'name' => 'Charity Gala Dinner',
            'description' => 'A night of elegance, entertainment, and fundraising for a noble cause.',
            'date' => '2024-12-01',
            'time' => '18:30:00',
            'location' => 'The Grand Ballroom, Beverly Hills',
            'available_seats' => 150,
            'price' => 299.99,
            'status' => 'Active',
            'created_at' => now(),
            ],
        ];
    }
}
