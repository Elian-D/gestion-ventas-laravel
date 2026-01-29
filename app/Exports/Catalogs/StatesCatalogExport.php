<?php 

// app/Exports/Catalogs/StatesCatalogExport.php
namespace App\Exports\Catalogs;

use App\Models\Geo\State;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatesCatalogExport implements FromCollection, WithHeadings
{
    public function collection() {
        // Usamos el helper para filtrar por el país configurado
        return State::where('country_id', general_config()->country_id)
            ->select('id', 'name')->get();
    }
    public function headings(): array { return ['ID (Referencia)', 'Nombre de Provincia/Estado']; }
}