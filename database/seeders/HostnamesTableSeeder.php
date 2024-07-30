<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Hostname; // Ensure this is the correct namespace for the Hostname model

class HostnamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hostname = Hostname::firstOrNew(['fqdn' => 'voyager.test']);

        if (!$hostname->exists) {
            $hostname->fill([
                'fqdn' => 'voyager.test',
            ])->save();
        }
    }
}
