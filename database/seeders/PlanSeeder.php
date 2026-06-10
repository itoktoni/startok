<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\Subscribe;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::truncate();
        Subscribe::truncate();

         $plan = [
            [
                'plan_nama' => 'Free Trial 10 Hari',
                'plan_status' => 1,
                'plan_keterangan' => '1 Anak Free untuk 10 hari',
                'plan_value' => 1,
                'plan_harga' => 0,
                'plan_fee' => 0,
                'plan_periode' => '10d',
                'plan_color' => 'rgb(178, 190, 181)',
                'plan_interval' => '10d',
                'plan_recomended' => 0,
            ],
            [
                'plan_nama' => 'Member',
                'plan_status' => 1,
                'plan_keterangan' => '1 Anak untuk 1 Tahun',
                'plan_value' => 1,
                'plan_harga' => 99000,
                'plan_fee' => 0,
                'plan_periode' => '1y',
                'plan_color' => 'rgb(109, 190, 123)',
                'plan_interval' => '1y',
                'plan_recomended' => 1,
            ],
            [
                'plan_nama' => 'Premium',
                'plan_status' => 1,
                'plan_keterangan' => 'Max 3 anak',
                'plan_value' => 1,
                'plan_harga' => 159,
                'plan_fee' => 0,
                'plan_periode' => '1y',
                'plan_color' => 'rgb(33, 150, 243)',
                'plan_interval' => '1y',
                'plan_recomended' => 1,
            ],
            [
                'plan_nama' => 'Family',
                'plan_status' => 1,
                'plan_keterangan' => 'Hingga 5 anak, bisa login 2 device sekaligus',
                'plan_value' => 3,
                'plan_harga' => 149000,
                'plan_fee' => 0,
                'plan_periode' => '1y',
                'plan_color' => 'rgb(233, 30, 99)',
                'plan_interval' => '1y',
                'plan_recomended' => '0',
            ],
        ];

        Plan::insert($plan);

    }
}
