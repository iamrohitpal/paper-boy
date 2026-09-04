<?php

namespace Database\Seeders;

use App\Models\Newspaper;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class NewspaperSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = Tenant::first()?->id ?? 1;

        $paperCatalog = [
            ['name' => 'The Times of India', 'publisher' => 'Bennett, Coleman & Co.', 'language' => 'English', 'mrp' => 5.00],
            ['name' => 'The Hindu', 'publisher' => 'Kasturi & Sons', 'language' => 'English', 'mrp' => 6.00],
            ['name' => 'Hindustan Times', 'publisher' => 'HT Media', 'language' => 'English', 'mrp' => 5.50],
            ['name' => 'Dainik Jagran', 'publisher' => 'Jagran Prakashan', 'language' => 'Hindi', 'mrp' => 4.50],
            ['name' => 'Amar Ujala', 'publisher' => 'Amar Ujala Ltd.', 'language' => 'Hindi', 'mrp' => 4.00],
            ['name' => 'Navbharat Times', 'publisher' => 'Bennett, Coleman & Co.', 'language' => 'Hindi', 'mrp' => 4.50],
            ['name' => 'The Economic Times', 'publisher' => 'Bennett, Coleman & Co.', 'language' => 'English', 'mrp' => 7.00],
            ['name' => 'Deccan Chronicle', 'publisher' => 'Deccan Chronicle Holdings', 'language' => 'English', 'mrp' => 4.00],
            ['name' => 'Punjab Kesari', 'publisher' => 'Hind Samachar Group', 'language' => 'Hindi', 'mrp' => 4.00],
            ['name' => 'Malayala Manorama', 'publisher' => 'MM Publications', 'language' => 'Regional', 'mrp' => 6.00],
        ];

        foreach ($paperCatalog as $catalogItem) {
            $mrp = $catalogItem['mrp'];
            Newspaper::firstOrCreate(
                ['name' => $catalogItem['name'], 'tenant_id' => $tenantId],
                [
                    'publisher' => $catalogItem['publisher'],
                    'language' => $catalogItem['language'],
                    'mrp' => $mrp,
                    'purchase_price' => round($mrp * 0.70, 2),
                    'selling_price' => $mrp,
                    'selling_price_sunday' => round($mrp * 1.25, 2),
                    'commission' => round($mrp * 0.30, 2),
                    'status' => 'Active',
                ]
            );
        }
    }
}
