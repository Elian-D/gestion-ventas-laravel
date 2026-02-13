<?php

namespace App\Services\Sales\Ncf;

use App\Models\Sales\Ncf\NcfType;
use App\Models\Sales\Ncf\NcfSequence;
use App\Models\Sales\Ncf\NcfLog;

class NcfCatalogService
{
    /**
     * Datos maestros de Tipos (Común para todos)
     */
    public function getTypesData(): array
    {
        $types = NcfType::where('is_active', true)->get();
        
        return [
            'ncf_types'          => $types->pluck('display_name', 'id'),
            'ncf_types_codes'    => $types->pluck('code', 'id'),
            'ncf_types_prefixes' => $types->pluck('prefix', 'id'),
            'ncf_requires_rnc'   => $types->pluck('requires_rnc', 'id'),
            // ESTA ES LA VARIABLE QUE FALTA:
            'ncf_types_electronic_status' => $types->pluck('is_electronic', 'id'),
        ];
    }

    /**
     * Específico para el INDEX de Secuencias (Lotes)
     */
    public function getForSequences(): array
    {
        return array_merge($this->getTypesData(), [
            'statuses' => [
                NcfSequence::STATUS_ACTIVE    => 'Activa',
                NcfSequence::STATUS_EXHAUSTED => 'Agotada',
                NcfSequence::STATUS_EXPIRED   => 'Vencida',
            ]
        ]);
    }

    /**
     * Específico para el INDEX de Auditoría (Logs)
     */
    public function getForLogs(): array
    {
        return array_merge($this->getTypesData(), [
            'statuses' => [
                NcfLog::STATUS_USED     => 'Utilizado',
                NcfLog::STATUS_VOIDED => 'Anulado',
            ]
        ]);
    }
}