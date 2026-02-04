<?php

namespace Database\Factories\Accounting;

use App\Models\Accounting\Receivable;
use App\Models\Accounting\AccountingAccount;
use App\Models\Accounting\DocumentType;
use App\Models\Clients\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceivableFactory extends Factory
{
    protected $model = Receivable::class;

    public function definition(): array
    {
        $totalAmount = fake()->randomFloat(2, 100, 5000);
        $emissionDate = fake()->dateTimeBetween('-2 months', 'now');
        
        // Buscamos el tipo de documento para facturas
        $docType = DocumentType::where('code', 'FAC')->first();
        $documentNumber = $docType 
            ? $docType->getNextNumberFormatted() 
            : 'FAC-' . fake()->unique()->numberBetween(1000, 9000);

        return [
            'client_id'             => Client::factory(), // Se sobreescribe en el ClientFactory
            'journal_entry_id'      => null, // Opcional: podrías vincular un asiento real aquí
            'accounting_account_id' => AccountingAccount::where('code', '1.1.02')->first()?->id,
            'document_number'       => $documentNumber,
            'description'           => 'Venta de productos/servicios - ' . fake()->sentence(3),
            'total_amount'          => $totalAmount,
            'current_balance'       => $totalAmount,
            'emission_date'         => $emissionDate,
            'due_date'              => \Carbon\Carbon::instance($emissionDate)->addDays(30),
            'status'                => Receivable::STATUS_UNPAID,
        ];
    }

    /**
     * Estado para facturas ya vencidas
     */
    public function overdue()
    {
        return $this->state(fn (array $attributes) => [
            'emission_date' => now()->subDays(45),
            'due_date'      => now()->subDays(15),
        ]);
    }
}