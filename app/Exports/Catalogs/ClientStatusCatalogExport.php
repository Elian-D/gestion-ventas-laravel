<?php

namespace App\Exports\Catalogs;

use App\Models\Configuration\EstadosCliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientStatusCatalogExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return EstadosCliente::select('nombre')->get();
    }

    public function headings(): array {
        return ['Estados de Cliente permitidos'];
    }
}