<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aset;
use Illuminate\Support\Facades\DB;

class TestFotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil aset pertama yang ada
        $aset = Aset::first();

        if ($aset) {
            // Update foto dengan contoh multiple foto (format JSON)
            $fotoPaths = [
                'foto_aset/test_foto_1.jpg',
                'foto_aset/test_foto_2.jpg',
                'foto_aset/test_foto_3.jpg',
                'foto_aset/test_foto_4.jpg'
            ];

            $aset->foto = json_encode($fotoPaths);
            $aset->save();

            $this->command->info("Updated aset {$aset->nama_brg} with multiple foto test data");
        } else {
            $this->command->info("No aset found to update");
        }
    }
}
