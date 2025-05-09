<?php

namespace App\Imports;

use App\Models\MapNivel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class MapNivelImport implements ToModel, WithHeadingRow,WithBatchInserts, WithChunkReading,WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        MapNivel::updateOrCreate(
            [
                'niv_nombre' => $row['niv_nombre'],
            ],[]
        );
        return null;
    }
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
            'delimiter' => ';', // Delimitador
        ];
    }
    public function batchSize(): int{
        return 1000;
    }
    public function chunkSize(): int{
        return 1000;
    }
    public function rules(): array{
        return [

        ];
    }
}
