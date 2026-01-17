<?php

namespace App\Imports;

use App\Models\Clients\Client;
use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;
use App\Models\Geo\State;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Validation\ValidationException;

class ClientsImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsEmptyRows
{
    private static $states;
    private static $estadosClientes;
    private static $taxTypes;
    private static $countryId;
    private static $initialized = false;
    private static $firstChunk = true;

    const EXPECTED_HEADERS = [
        'tipo', 'nombre_o_razon_social', 'nombre_comercial', 'email', 'telefono',
        'provincia_estado', 'ciudad', 'tipo_identificacion', 'rnc_cedula',
        'estado_cliente', 'activo',
    ];

    public function __construct()
    {
        if (!self::$initialized) {
            self::$countryId = general_config()->country_id;
            
            self::$states = State::where('country_id', self::$countryId)
                ->pluck('id', 'name')->toArray();
                
            self::$estadosClientes = EstadosCliente::pluck('id', 'nombre')->toArray();
            
            self::$taxTypes = TaxIdentifierType::where('country_id', self::$countryId)
                ->pluck('id', 'name')->toArray();
                
            self::$initialized = true;
        }
    }

    public function collection(Collection $rows)
    {
        // Validar headers solo una vez
        if (self::$firstChunk && $rows->isNotEmpty()) {
            $this->validateHeaders($rows->first());
            self::$firstChunk = false;
        }

        $dataToUpsert = [];
        $taxIds = [];

        foreach ($rows as $row) {
            if (!$this->validateRow($row)) continue;

            $taxId = $row['rnc_cedula'];
            
            if (in_array($taxId, $taxIds)) continue;
            $taxIds[] = $taxId;

            $dataToUpsert[] = [
                'type'                   => strtolower($row['tipo']) == 'empresa' ? 'company' : 'individual',
                'estado_cliente_id'      => self::$estadosClientes[$row['estado_cliente']] ?? null,
                'name'                   => $row['nombre_o_razon_social'],
                'commercial_name'        => $row['nombre_comercial'] ?? null,
                'email'                  => $row['email'] ?? null,
                'phone'                  => $row['telefono'] ?? null,
                'state_id'               => self::$states[$row['provincia_estado']] ?? null,
                'city'                   => $row['ciudad'],
                'tax_identifier_type_id' => self::$taxTypes[$row['tipo_identificacion']] ?? null,
                'tax_id'                 => $taxId,
                'active'                 => strtolower($row['activo'] ?? 'si') == 'si',
                'updated_at'             => now(),
                'created_at'             => now(),
            ];
        }

        if (!empty($dataToUpsert)) {
            // ✅ OPTIMIZACIÓN CRÍTICA: Usar transacción y deshabilitar checks temporalmente
            DB::transaction(function () use ($dataToUpsert) {
                // Desactivar verificaciones FK temporalmente
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                
                Client::upsert(
                    $dataToUpsert,
                    ['tax_id'],
                    ['type', 'estado_cliente_id', 'name', 'commercial_name', 'email', 
                     'phone', 'state_id', 'city', 'tax_identifier_type_id', 'active', 'updated_at']
                );
                
                // Reactivar verificaciones
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            });
        }
    }

    private function validateHeaders($firstRow)
    {
        $fileHeaders = array_keys($firstRow->toArray());

        $missing = array_diff(self::EXPECTED_HEADERS, $fileHeaders);
        if (!empty($missing)) {
            throw ValidationException::withMessages([
                'file' => "Faltan columnas: " . implode(', ', $missing)
            ]);
        }

        $extra = array_diff($fileHeaders, self::EXPECTED_HEADERS);
        if (!empty($extra)) {
            throw ValidationException::withMessages([
                'file' => "Columnas adicionales: " . implode(', ', $extra)
            ]);
        }

        if ($fileHeaders !== self::EXPECTED_HEADERS) {
            throw ValidationException::withMessages([
                'file' => "Orden incorrecto de columnas."
            ]);
        }
    }

    private function validateRow($row): bool
    {
        if (empty($row['rnc_cedula'])) return false;
        if (empty($row['tipo']) || !in_array(strtolower($row['tipo']), ['individual', 'empresa'])) return false;
        if (empty($row['nombre_o_razon_social'])) return false;
        if (empty($row['ciudad'])) return false;
        if (!isset(self::$states[$row['provincia_estado']])) return false;
        if (!isset(self::$estadosClientes[$row['estado_cliente']])) return false;
        if (!isset(self::$taxTypes[$row['tipo_identificacion']])) return false;

        return true;
    }

    public function chunkSize(): int { return 1000; }
}