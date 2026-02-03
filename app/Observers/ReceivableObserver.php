<?php

namespace App\Observers;

use App\Models\Accounting\Receivable;

class ReceivableObserver
{

    public function saved(Receivable $receivable)
    {
        // Al usar saved, cubrimos tanto 'created' como 'updated'
        if ($receivable->client) {
            $receivable->client->refreshBalance();
        }
    }

    public function deleted(Receivable $receivable)
    {
        $receivable->client->refreshBalance();
    }
    
    public function restored(Receivable $receivable)
    {
        $receivable->client->refreshBalance();
    }
}