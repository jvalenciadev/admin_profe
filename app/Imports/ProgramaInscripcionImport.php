<?php

namespace App\Imports;

use App\Models\ProgramaInscripcion;
use App\Models\MapPersona;

use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Carbon\Carbon;





class ProgramaInscripcionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading,WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   
    private $map_persona;
    public function __construct()
    {
        $this->map_persona = MapPersona::pluck('per_id', 'per_rda');
    }
    public function model(array $row)
    {
 
        $inscripcion = ProgramaInscripcion::where('per_id', $this->map_persona[$row['per_rda']])
            ->first();

        if ($inscripcion) {
            // Desactivar temporalmente timestamps
            $inscripcion->timestamps = false;
            // Asegurarse de que 'created_at' está en el formato correcto
            try {
                // Formato original de la fecha con hora desde el archivo
                $dateWithTime = $row['created_at']; // '29/4/2024 20:11'

                // Convertir a formato Carbon esperado por Laravel (Y-m-d H:i:s)
                $updatedDateTime = Carbon::createFromFormat('d/m/Y H:i', $dateWithTime)->format('Y-m-d H:i:s');

                // Actualizar el registro con la fecha y hora correctas
                $inscripcion->update([
                    'created_at' => $updatedDateTime, // Usar el objeto Carbon
                ]);
            } catch (\Exception $e) {
                // Manejar excepciones en caso de error al analizar la fecha
                // Puedes registrar el error o manejarlo según sea necesario
                \Log::error("Error al analizar la fecha: " . $e->getMessage());
            }
            // Volver a activar timestamps si es necesario
            $inscripcion->timestamps = true;
        }
        // ProgramaInscripcion::create(
        //     [
        //         'per_id' =>  $this->map_persona[$row['per_rda']],
        //         'pro_id' => $row['pro_id'],
        //         'pro_tur_id' => $row['pro_tur_id'],
        //         'sede_id' => $row['sede_id'],
        //         'pie_id' => 2,
        //     ]
        // );
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
