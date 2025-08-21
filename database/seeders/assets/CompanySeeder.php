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
                'name'      => 'Rentalku',
                'web'       => 'https://nusantaratech.co.id',
                'phone'     => "023123232",
                'address'   => 'Rentalku',
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
