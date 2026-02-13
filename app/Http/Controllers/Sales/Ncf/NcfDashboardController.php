<?php

namespace App\Http\Controllers\Sales\Ncf;

use App\Http\Controllers\Controller;
use App\Models\Sales\Ncf\NcfType;
use App\Models\Sales\Ncf\NcfSequence;
use App\Models\Sales\Ncf\NcfLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NcfDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // ===== SISTEMA DE FILTROS =====
        $range = $request->get('range', '30days');
        $ncfTypeFilter = $request->get('ncf_type', null);
        $statusFilter = $request->get('status', null);
        
        $startDay = now()->subDays(30)->startOfDay();
        $endDay = now()->endOfDay();

        switch ($range) {
            case 'today':
                $startDay = now()->startOfDay();
                break;
            case '7days':
                $startDay = now()->subDays(7)->startOfDay();
                break;
            case 'this_month':
                $startDay = now()->startOfMonth()->startOfDay();
                break;
            case 'this_year':
                $startDay = now()->startOfYear()->startOfDay();
                break;
            case 'custom':
                $startDay = Carbon::parse($request->get('start_date'))->startOfDay();
                $endDay = Carbon::parse($request->get('end_date'))->endOfDay();
                break;
        }

        // ===== 1. KPIs PRINCIPALES =====
        $stats = $this->calculateKPIs($startDay, $endDay, $ncfTypeFilter);

        // ===== 2. GRÁFICOS =====
        $charts = [
            'usage_by_type' => $this->getUsageByType($startDay, $endDay, $ncfTypeFilter),
            'timeline' => $this->getEmissionTimeline($startDay, $endDay, $ncfTypeFilter),
            'sequence_progress' => $this->getSequenceProgress($ncfTypeFilter),
            'cancellation_reasons' => $this->getCancellationReasons($startDay, $endDay, $ncfTypeFilter),
        ];

        // ===== 3. TABLAS DE CONTROL =====
        $activeSequences = $this->getActiveSequencesWithDetails($statusFilter, $ncfTypeFilter);
        $recentLogs = $this->getRecentLogs($startDay, $endDay, $ncfTypeFilter);
        $criticalAlerts = $this->getCriticalAlerts($ncfTypeFilter);

        // ===== 4. DATOS PARA FILTROS =====
        $ncfTypes = NcfType::where('is_active', true)->get();
        $availableStatuses = NcfSequence::getStatuses();

        return view('sales.ncf.dashboard', [
            'stats' => $stats,
            'charts' => $charts,
            'activeSequences' => $activeSequences,
            'recentLogs' => $recentLogs,
            'criticalAlerts' => $criticalAlerts,
            'ncfTypes' => $ncfTypes,
            'availableStatuses' => $availableStatuses,
            'filters' => [
                'start' => $startDay->format('Y-m-d'),
                'end' => $endDay->format('Y-m-d'),
                'current_range' => $range,
                'ncf_type' => $ncfTypeFilter,
                'status' => $statusFilter,
            ]
        ]);
    }

    // ===== CÁLCULO DE KPIs =====
    private function calculateKPIs($start, $end, $ncfTypeFilter = null)
    {
        // Query base para Secuencias (Disponibilidad y Alertas)
        $seqQuery = NcfSequence::where('status', NcfSequence::STATUS_ACTIVE)
            ->where('expiry_date', '>', now())
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter));

        // 1. NCF Disponibles Totales
        $totalAvailable = $seqQuery->get()->sum(fn($seq) => max(0, $seq->to - $seq->current));

        // 2. Secuencias en Alerta
        $sequencesInAlert = $seqQuery->get()->filter(fn($seq) => $seq->isLow())->count();

        // 3. Próximo Vencimiento
        $nextExpiry = (clone $seqQuery)->orderBy('expiry_date', 'asc')->first();
        $nextExpiryDate = $nextExpiry ? $nextExpiry->expiry_date : null;
        $daysUntilExpiry = $nextExpiryDate ? (int) now()->diffInDays($nextExpiryDate, false) : null;

        // Query base para Logs (Consumo y Anulaciones)
        $logQuery = NcfLog::whereBetween('created_at', [$start, $end])
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter));

        // 4. Total Usado y Anulado
        $totalUsed = (clone $logQuery)->where('status', NcfLog::STATUS_USED)->count();
        $voidedLogs = (clone $logQuery)->where('status', NcfLog::STATUS_VOIDED)->count();
        $totalLogs = $totalUsed + $voidedLogs;

        // 5. Consumo Diario Promedio
        $totalDays = max(1, $start->diffInDays($end));
        $dailyAverage = round($totalUsed / $totalDays, 1);

        // 6. Proyección de Agotamiento (días restantes según consumo actual)
        $daysRemaining = $dailyAverage > 0 ? (int) floor($totalAvailable / $dailyAverage) : null;

        // 7. Tasa de Anulación
        $voidRate = $totalLogs > 0 ? round(($voidedLogs / $totalLogs) * 100, 1) : 0;

        return [
            'total_available' => $totalAvailable,
            'sequences_in_alert' => $sequencesInAlert,
            'next_expiry_date' => $nextExpiryDate,
            'days_until_expiry' => $daysUntilExpiry,
            'daily_average' => $dailyAverage,
            'days_remaining' => $daysRemaining,
            'void_rate' => $voidRate,
            'total_emitted' => $totalUsed,
            'total_voided' => $voidedLogs,
        ];
    }

    // ===== GRÁFICO: Uso de NCF por Tipo =====
    private function getUsageByType($start, $end, $ncfTypeFilter = null)
    {
        $data = NcfLog::select('ncf_types.name', DB::raw('COUNT(*) as total'))
            ->join('ncf_types', 'ncf_logs.ncf_type_id', '=', 'ncf_types.id')
            ->whereBetween('ncf_logs.created_at', [$start, $end])
            ->where('ncf_logs.status', NcfLog::STATUS_USED)
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_logs.ncf_type_id', $ncfTypeFilter))
            ->groupBy('ncf_types.id', 'ncf_types.name')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $data->pluck('name'),
            'values' => $data->pluck('total'),
        ];
    }

    // ===== GRÁFICO: Línea de Tiempo de Emisión =====
    private function getEmissionTimeline($start, $end, $ncfTypeFilter = null)
    {
        return NcfLog::select(
                DB::raw("DATE_FORMAT(created_at, '%d %b') as date"),
                DB::raw('COUNT(*) as total'),
                DB::raw('MIN(created_at) as sort_date')
            )
            ->whereBetween('created_at', [$start, $end])
            ->where('status', NcfLog::STATUS_USED)
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter))
            ->groupBy('date')
            ->orderBy('sort_date')
            ->get()
            ->map(fn($item) => [
                'date' => $item->date,
                'total' => $item->total,
            ]);
    }

    // ===== GRÁFICO: Progreso de Secuencias =====
    private function getSequenceProgress($ncfTypeFilter = null)
    {
        return NcfSequence::with('type')
            ->where('status', NcfSequence::STATUS_ACTIVE)
            ->where('expiry_date', '>', now())
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter))
            ->get()
            ->map(function($seq) {
                $total = $seq->to - $seq->from + 1;
                $used = ($seq->current - $seq->from) + 1;
                $percentage = $total > 0 ? round(($used / $total) * 100, 1) : 0;

                return [
                    'label' => $seq->type->name . ' - ' . $seq->series,
                    'used' => $used,
                    'total' => $total,
                    'percentage' => $percentage,
                    'remaining' => $seq->to - $seq->current,
                ];
            });
    }

    // ===== GRÁFICO: Motivos de Anulación =====
    private function getCancellationReasons($start, $end, $ncfTypeFilter = null)
    {
        $data = NcfLog::select('cancellation_reason', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->where('status', NcfLog::STATUS_VOIDED)
            ->whereNotNull('cancellation_reason')
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter))
            ->groupBy('cancellation_reason')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $data->pluck('cancellation_reason'),
            'values' => $data->pluck('total'),
        ];
    }

    // ===== TABLA: Secuencias Activas con Detalles =====
    private function getActiveSequencesWithDetails($statusFilter = null, $ncfTypeFilter = null)
    {
        $query = NcfSequence::with('type')
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter))
            ->orderBy('expiry_date', 'asc');

        // Aplicar filtro de estado si existe
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        } else {
            // Por defecto, mostrar solo activas y próximas a vencer
            $query->whereIn('status', [NcfSequence::STATUS_ACTIVE]);
        }

        return $query->get()->map(function($seq) {
            $total = $seq->to - $seq->from + 1;
            $used = ($seq->current - $seq->from) + 1;
            $remaining = $seq->to - $seq->current;
            $progress = $total > 0 ? round(($used / $total) * 100, 1) : 0;
            $daysToExpiry = (int) now()->diffInDays($seq->expiry_date, false);

            return (object)[
                'id' => $seq->id,
                'type_name' => $seq->type->name,
                'type_code' => $seq->type->code,
                'series' => $seq->series,
                'from' => $seq->from,
                'to' => $seq->to,
                'current' => $seq->current,
                'used' => $used,
                'remaining' => $remaining,
                'progress' => $progress,
                'expiry_date' => $seq->expiry_date,
                'days_to_expiry' => $daysToExpiry,
                'calculated_status' => $seq->calculated_status,
                'status_label' => $seq->status_label,
                'status_styles' => $seq->status_styles,
                'is_low' => $seq->isLow(),
            ];
        });
    }

    // ===== TABLA: Logs Recientes =====
    private function getRecentLogs($start, $end, $ncfTypeFilter = null)
    {
        $query = NcfLog::with(['sale.client', 'type', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->latest();

        if ($ncfTypeFilter) {
            $query->where('ncf_type_id', $ncfTypeFilter);
        }

        return $query->take(15)->get();
    }

    // ===== ALERTAS CRÍTICAS =====
    private function getCriticalAlerts($ncfTypeFilter = null)
    {
        $alerts = [];

        // Secuencias vencidas
        $expired = NcfSequence::with('type')
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter))
            ->where(function($q) {
                $q->where('status', NcfSequence::STATUS_EXPIRED)
                  ->orWhere(function($q2) {
                      $q2->where('expiry_date', '<', now())
                         ->where('status', '!=', NcfSequence::STATUS_EXPIRED);
                  });
            })
            ->get();

        foreach ($expired as $seq) {
            $alerts[] = [
                'type' => 'danger',
                'color' => 'red',
                'title' => 'Secuencia Vencida',
                'message' => "{$seq->type->name} ({$seq->series}) venció el {$seq->expiry_date->format('d/m/Y')}. Solicitar nueva autorización a DGII.",
                'sequence_id' => $seq->id,
            ];
        }

        // Secuencias agotadas
        $exhausted = NcfSequence::with('type')
            ->where('status', NcfSequence::STATUS_ACTIVE)
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter))
            ->get()
            ->filter(fn($seq) => ($seq->to - $seq->current) <= 0);

        foreach ($exhausted as $seq) {
            $alerts[] = [
                'type' => 'danger',
                'color' => 'red',
                'title' => 'Secuencia Agotada',
                'message' => "{$seq->type->name} ({$seq->series}) no tiene más correlativos disponibles.",
                'sequence_id' => $seq->id,
            ];
        }

        // Secuencias en alerta (bajas)
        $low = NcfSequence::with('type')
            ->where('status', NcfSequence::STATUS_ACTIVE)
            ->where('expiry_date', '>', now())
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter))
            ->get()
            ->filter(fn($seq) => $seq->isLow() && ($seq->to - $seq->current) > 0);

        foreach ($low as $seq) {
            $remaining = $seq->to - $seq->current;
            $alerts[] = [
                'type' => 'warning',
                'color' => 'orange',
                'title' => 'Secuencia por Agotarse',
                'message' => "{$seq->type->name} ({$seq->series}) solo tiene {$remaining} NCF disponibles. Solicitar nueva secuencia.",
                'sequence_id' => $seq->id,
            ];
        }

        // Próximos a vencer (15 días o menos)
        $expiringSoon = NcfSequence::with('type')
            ->where('status', NcfSequence::STATUS_ACTIVE)
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(15))
            ->when($ncfTypeFilter, fn($q) => $q->where('ncf_type_id', $ncfTypeFilter))
            ->get();

        foreach ($expiringSoon as $seq) {
            $daysLeft = (int) now()->diffInDays($seq->expiry_date, false);
            $alerts[] = [
                'type' => 'warning',
                'color' => 'yellow',
                'title' => 'Próximo a Vencer',
                'message' => "{$seq->type->name} ({$seq->series}) vence en {$daysLeft} días ({$seq->expiry_date->format('d/m/Y')}).",
                'sequence_id' => $seq->id,
            ];
        }

        return collect($alerts);
    }
}
