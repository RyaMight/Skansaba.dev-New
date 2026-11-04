<?php

namespace Database\Seeders;

use App\Models\ProspekKarir;
use Illuminate\Database\Seeder;

class ProspekKarirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data disinkronkan dari https://skansaba.dev
     */
    public function run(): void
    {
        ProspekKarir::truncate();

        $karir = [
            ['title' => 'Programmer / Software Developer', 'order' => 1],
            ['title' => 'Web Developer (Frontend / Backend)', 'order' => 2],
            ['title' => 'Mobile App Developer', 'order' => 3],
            ['title' => 'Software Tester / Quality Assurance (QA)', 'order' => 4],
            ['title' => 'IT Support / Technical Support', 'order' => 5],
            ['title' => 'Freelancer / Remote Developer', 'order' => 6],
            ['title' => 'Wirausaha (Software House / Startup)', 'order' => 7],
        ];

        foreach ($karir as $item) {
            ProspekKarir::create([
                'title' => $item['title'],
                'description' => $item['title'],
                'icon' => null,
                'image' => null,
                'order' => $item['order'],
                'is_active' => true,
            ]);
        }

        $this->command->info('✓ Prospek Karir seeded successfully (' . count($karir) . ' records) - data skansaba.dev');
    }
}
