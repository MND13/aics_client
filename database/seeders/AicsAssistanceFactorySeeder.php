<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AicsAssistance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AicsAssistanceFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create()->first();
        $user->assignRole('user');
        #$this->actingAs($user);
        Auth::loginUsingId($user->id);
        $assistance_request = AicsAssistance::factory(5)->create();
    }
}
