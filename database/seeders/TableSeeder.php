<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [];

        for ($i = 1; $i <= 12; $i++) {
            $tableNumber = 'A' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $qrCode = hash('crc32', $tableNumber); // Hashing CRC32 (lebih pendek dari MD5)

            $tables[] = [
                'table_number' => $tableNumber,
                'qr_code' => $qrCode
            ];
        }

        Table::insert($tables);
    }
}
