<?php

namespace App\Imports;

use App\Models\Clients\Client;
use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;
use App\Models\Geo\State;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ClientsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, WithUpserts, SkipsEmptyRows
{
    private $states;
    private $estadosClientes;
    private $taxTypes;

    public function __construct()
    {
        // Pluck nombre => id para búsqueda rápida en memoria
        $this->states = State::where('country_id', general_config()->country_id)
            ->pluck('id', 'name')->toArray();
            
        $this->estadosClientes = EstadosCliente::pluck('id', 'nombre')->toArray();
        
        $this->taxTypes = TaxIdentifierType::where('country_id', general_config()->country_id)
            ->pluck('id', 'name')->toArray();
    }

    /**
     * Estructura exacta y ordenada esperada.
     * IMPORTANTE: El orden de las columnas definido aquí debe coincidir exactamente con el del archivo importado.
     */
    const EXPECTED_HEADERS = [
        'tipo',
        'nombre_o_razon_social',
        'nombre_comercial',
        'email',
        'telefono',
        'provincia_estado',
        'ciudad',
        'tipo_identificacion',
        'rnc_cedula',
        'estado_cliente',
        'activo',
    ];

    public function prepareForValidation($data, $index)
    {
        if ($index === 2) {
            $fileHeaders = array_keys($data);

            // 1. Validar si faltan columnas
            $missing = array_diff(self::EXPECTED_HEADERS, $fileHeaders);
            if (!empty($missing)) {
                $names = implode(', ', $missing);
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'file' => "Estructura inválida. Faltan las siguientes columnas: [ $names ]"
                ]);
            }

            // 2. Validar si sobran columnas (columnas extra no permitidas)
            $extra = array_diff($fileHeaders, self::EXPECTED_HEADERS);
            if (!empty($extra)) {
                $names = implode(', ', $extra);
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'file' => "El archivo contiene columnas adicionales no permitidas: [ $names ]"
                ]);
            }

            // 3. Validar el orden exacto
            // Comparamos los arrays directamente. Al llegar aquí ya sabemos que tienen los mismos elementos,
            // así que si la igualdad falla, es puramente por el orden.
            if ($fileHeaders !== self::EXPECTED_HEADERS) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'file' => "Las columnas están en un orden incorrecto. Por favor, utilice la plantilla oficial sin mover las cabeceras."
                ]);
            }
        }

        return $data;
    }
    
    /**
     * Definimos qué columna es la que determina si un registro es duplicado.
     * En este caso, el RNC/Cédula (tax_id).
     */
    public function uniqueBy()
    {
        return 'tax_id';
    }

    public function model(array $row)
    {
        // Si la columna crítica está vacía tras el mapeo, ignoramos la fila o lanzamos error
        if (!isset($row['rnc_cedula']) || empty($row['rnc_cedula'])) {
            return null; 
        }
        return new Client([
            'type'                   => strtolower($row['tipo']) == 'empresa' ? 'company' : 'individual',
            'estado_cliente_id'      => $this->estadosClientes[$row['estado_cliente']] ?? null,
            'name'                   => $row['nombre_o_razon_social'],
            'commercial_name'        => $row['nombre_comercial'] ?? null,
            'email'                  => $row['email'] ?? null,
            'phone'                  => $row['telefono'] ?? null,
            'state_id'               => $this->states[$row['provincia_estado']] ?? null,
            'city'                   => $row['ciudad'],
            'tax_identifier_type_id' => $this->taxTypes[$row['tipo_identificacion']] ?? null,
            'tax_id'                 => $row['rnc_cedula'] ?? null,
            'active'                 => strtolower($row['activo'] ?? 'si') == 'si',
        ]);
    }

    public function rules(): array
    {
        return [
            'tipo' => 'required|in:Individual,Empresa,individual,empresa',
            'nombre_o_razon_social' => 'required|string|max:255',
            'provincia_estado' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!isset($this->states[$value])) {
                        $fail("La provincia '$value' no es válida para el país configurado.");
                    }
                },
            ],
            'estado_cliente' => 'required',
            'ciudad' => 'required|string',

            'tipo_identificacion' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!isset($this->taxTypes[$value])) {
                        $fail("El tipo de identificación '$value' no es válido.");
                    }
                },
            ],
            
            ];
    }

        /**
     * Personalización de los nombres de los atributos para los errores
     */
    public function customValidationAttributes()
    {
        return [
            'tipo' => 'tipo de cliente',
            'nombre_o_razon_social' => 'nombre del cliente',
            'provincia_estado' => 'provincia',
            'estado_cliente' => 'estado del cliente',
            'rnc_cedula.required' => 'El número de identificación (RNC/Cédula) es obligatorio.',
        ];
    }

    public function batchSize(): int { return 1000; }
    public function chunkSize(): int { return 1000; }
}