<?php

namespace Database\Seeders;

use Database\{
    Seeders\assets\BranchSeeder,
    Seeders\assets\CompanySeeder,
    Seeders\assets\RoleSeeder,
    Seeders\assets\UserSeeder
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
