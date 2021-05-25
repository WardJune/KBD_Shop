<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductImport implements WithStartRow, WithChunkReading
{
    /**
    * @param Collection $collection
    */

    use Importable;

    // mulai reading data exel dari baris ke 2 , karena baris pertama digunakan sebagai heading
    public function startRow(): int
    {
        return 2;
    }

    // menggontroll penggunaan memory dengan membatasi load data dalam sekali proses 
    public function chunkSize(): int
    {
        return 100;
    }
}