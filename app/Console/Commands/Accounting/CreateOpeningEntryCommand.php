<?php

namespace App\Console\Commands\Accounting;

use Illuminate\Console\Command;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\AccountingAccount;
use Illuminate\Support\Facades\DB;

class CreateOpeningEntryCommand extends Command
{
    protected $signature = 'accounting:opening {cash=0} {capital=0}';
    protected $description = 'Crea el asiento de apertura inicial para fondear la empresa';

    // Ejemplo comando: php artisan accounting:opening 50000 50000

    public function handle()
    {
        $cashAmount = (float) $this->argument('cash');
        $capitalAmount = (float) $this->argument('capital');

        if ($cashAmount <= 0 || $capitalAmount <= 0) {
            $this->error('Los montos deben ser mayores a cero.');
            return;
        }

        DB::transaction(function () use ($cashAmount, $capitalAmount) {
            // Buscamos las cuentas hijas (selectables)
            $cashAcc = AccountingAccount::where('code', '1.1.01')->first(); // O 1.1.01.01 si creaste la hija
            $capitalAcc = AccountingAccount::where('code', '3.1.01')->first();

            if (!$cashAcc || !$capitalAcc) {
                throw new \Exception("Asegúrate de haber ejecutado el seeder de cuentas primero.");
            }

            $entry = JournalEntry::create([
                'entry_date' => now(),
                'reference' => 'APERTURA-2026',
                'description' => 'Asiento de apertura manual - Fondo Inicial',
                'status' => JournalEntry::STATUS_POSTED,
                'created_by' => 1 // ID del Admin
            ]);

            // DEBE: Caja
            $entry->items()->create([
                'accounting_account_id' => $cashAcc->id,
                'debit' => $cashAmount,
                'credit' => 0,
                'note' => 'Carga inicial de efectivo'
            ]);

            // HABER: Capital
            $entry->items()->create([
                'accounting_account_id' => $capitalAcc->id,
                'debit' => 0,
                'credit' => $capitalAmount,
                'note' => 'Aporte inicial de capital'
            ]);

            $this->info("¡Asiento de apertura creado con éxito!");
        });
    }
}