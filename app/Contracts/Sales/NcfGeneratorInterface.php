<?php

namespace App\Contracts\Sales;

use App\Models\Sales\Sale;
use App\Models\Sales\Ncf\NcfType;

interface NcfGeneratorInterface
{
    /**
     * Genera el siguiente número de comprobante para una venta.
     * * @param Sale $sale La instancia de la venta
     * @param int $ncfTypeId El ID del tipo de comprobante solicitado
     * @return string El NCF completo (ej: B0100000005)
     */
    public function generate(Sale $sale, int $ncfTypeId): string;

    /**
     * Valida si hay disponibilidad para un tipo de comprobante.
     */
    public function hasAvailability(int $ncfTypeId): bool;
}