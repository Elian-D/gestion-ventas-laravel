<?php

namespace Database\Factories\Clients;

use App\Models\Clients\Client;
use App\Models\Accounting\{AccountingAccount, Receivable, Payment, DocumentType};
use App\Models\Configuration\{EstadosCliente, TaxIdentifierType};
use App\Models\Geo\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected int $countryId;

    public function __construct(...$args)
    {
        parent::__construct(...$args);
        // Asegúrate de que esta función helper exista o usa un valor por defecto
        $this->countryId = function_exists('general_config') ? general_config()->country_id : 1;
    }

    /**
     * Define el estado básico del modelo.
     * ESTO ES LO QUE FALTABA
     */
    public function definition(): array
    {
        $type = fake()->boolean(50) ? 'individual' : 'company';

        return [
            'type' => $type,
            'estado_cliente_id' => EstadosCliente::inRandomOrder()->value('id') ?? 1,
            'name' => $type === 'individual' ? fake()->name() : fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'state_id' => State::where('country_id', $this->countryId)->inRandomOrder()->value('id') ?? 1,
            'city' => fake()->city(),
            'address' => fake()->address(),
            'tax_identifier_type_id' => TaxIdentifierType::where('country_id', $this->countryId)->inRandomOrder()->value('id') ?? 1,
            'tax_id' => fake()->numerify('###########'),
            'credit_limit' => fake()->randomElement([5000, 10000, 20000]),
            'balance' => 0, // Empezamos en 0 para que afterCreating cree la deuda real
            'payment_terms' => 30,
            'accounting_account_id' => null, // Se asigna en configure()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Client $client) {
            // 1. Asegurar cuenta contable
            if (!$client->accounting_account_id) {
                $client->update([
                    'accounting_account_id' => AccountingAccount::where('code', '1.1.02')->first()?->id
                ]);
            }

            // 2. Generar facturas (Receivables) reales
            Receivable::factory()
                ->count(fake()->numberBetween(1, 3))
                ->create([
                    'client_id' => $client->id,
                    'accounting_account_id' => $client->accounting_account_id,
                ])
                ->each(function ($receivable) use ($client) {
                    // 3. Crear pagos parciales
                    if (fake()->boolean(70)) { 
                        $amountToPay = fake()->randomFloat(2, 10, $receivable->total_amount * 0.5);
                        $this->createRealPayment($client, $receivable, $amountToPay);
                    }
                });

            // 4. Sincronizar balance
            $client->refreshBalance();
        });
    }

    protected function createRealPayment($client, $receivable, $amount)
    {
        $docType = DocumentType::where('code', 'PAG')->first();
        $receiptNumber = $docType ? $docType->getNextNumberFormatted() : 'REC-' . fake()->numberBetween(100, 999);

        Payment::create([
            'client_id' => $client->id,
            'receivable_id' => $receivable->id,
            'tipo_pago_id' => 1,
            'receipt_number' => $receiptNumber,
            'amount' => $amount,
            'payment_date' => now(),
            'status' => 'active',
            'created_by' => 1
        ]);

        $receivable->decrement('current_balance', $amount);
        if ($docType) $docType->increment('current_number');
    }
}