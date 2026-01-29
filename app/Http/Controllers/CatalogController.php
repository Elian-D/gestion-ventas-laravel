<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Catalogs\StatesCatalogExport;
use App\Exports\Catalogs\ClientStatusCatalogExport;
use App\Exports\Catalogs\TaxTypesCatalogExport;

class CatalogController extends Controller
{
    /**
     * Catálogo de Provincias/Estados (Basado en Configuración General)
     */
    public function states()
    {
        return Excel::download(new StatesCatalogExport, 'catalogo-provincias.xlsx');
    }

    /**
     * Catálogo de Tipos de Identificación (RNC, Cédula, Pasaporte, etc.)
     */
    public function taxTypes()
    {
        return Excel::download(new TaxTypesCatalogExport, 'catalogo-tipos-identificacion.xlsx');
    }

    /**
     * Catálogo de Estados (Específico de clientes, pero centralizado aquí)
     */
    public function clientStatus()
    {
        return Excel::download(new ClientStatusCatalogExport, 'catalogo-estados-cliente.xlsx');
    }
}