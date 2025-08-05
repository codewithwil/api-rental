<?php

namespace Database\Seeders\assets;

use App\{
    Models\Resources\Company\Company
};

use Illuminate\{
    Database\Seeder
};

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'companyId' => 1,
                'image'     => 'company1.png',
                'name'      => 'Rentalku',
                'web'       => 'https://nusantaratech.co.id',
            ],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(
                ['companyId' => $company['companyId']],
                $company
            );
        }
    }
}
