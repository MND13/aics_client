<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        #\App\Models\User::factory(10)->create();
        $this->call(PsgcSeeder::class);
        $this->call(AicsTypeSeeder::class);
        $this->call(AicsRequirementSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(SubcategoriesSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(OfficesSeeder::class);
        $this->call(AssessmentOptsSeeder::class);
        $this->call(FundSourceSeeder::class);
        $this->call(ProvidersSeeder::class);
        $this->call(SignatoriesSeeder::class);

     
    }
}
