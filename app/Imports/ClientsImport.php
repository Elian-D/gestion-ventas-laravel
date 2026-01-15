<?php

namespace App\Imports;

use App\Models\Clients\Client;
use App\Models\Configuration\EstadosCliente;
use App\Models\Configuration\TaxIdentifierType;
use App\Models\Geo\State;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ClientsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
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

    public function model(array $row)
    {
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
        ];
    }

    public function batchSize(): int { return 1000; }
    public function chunkSize(): int { return 1000; }
}