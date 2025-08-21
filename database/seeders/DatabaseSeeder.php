<?php

namespace Database\Seeders;

use Database\{
    Seeders\assets\BranchSeeder,
    Seeders\assets\CompanySeeder,
    Seeders\assets\RoleSeeder,
    Seeders\assets\UserSeeder,
    Seeders\assets\BrandSeeder,
    Seeders\assets\CategorySeeder,
    Seeders\assets\RulesSeeder
};

use Illuminate\{
    Database\Seeder,
    Support\Facades\DB
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $this->call([
                CompanySeeder::class,  
                BranchSeeder::class,  
                BrandSeeder::class,  
                CategorySeeder::class,  
                RulesSeeder::class,  
                RoleSeeder::class,
                UserSeeder::class,    
            ]);
                DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
        }
    }
}
