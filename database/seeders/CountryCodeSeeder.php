<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\CountryCode;

class CountryCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://restcountries.com/v3.1/all');
        if ($response->failed()) {
            $this->command->error('Failed to fetch country codes');
            return;
        }

        $countries = $response->json();

        foreach ($countries as $country) {
            if (isset($country['cca2']) && isset($country['name']['common']) && isset($country['idd']['root']) && isset($country['idd']['suffixes'][0])) {
                CountryCode::updateOrCreate(
                    ['code' => $country['cca2']],
                    [
                        'name' => $country['name']['common'],
                        'dial_code' => $country['idd']['root'] . $country['idd']['suffixes'][0],
                    ]
                );
            }
        }

        $this->command->info('Country codes have been successfully fetched and stored.');
    }
}
