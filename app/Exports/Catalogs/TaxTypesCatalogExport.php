<?php 

// app/Exports/Catalogs/TaxTypesCatalogExport.php
namespace App\Exports\Catalogs;

use App\Models\Configuration\TaxIdentifierType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaxTypesCatalogExport implements FromCollection, WithHeadings
{
    public function collection() {
        // Usamos el helper para filtrar por el país configurado
        return TaxIdentifierType::where('country_id', general_config()->country_id)
            ->select('id', 'name')->get();
    }
    public function headings(): array { return ['ID (Referencia)', 'Nombre de Tipo de Identificación']; }
}