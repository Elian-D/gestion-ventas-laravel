<?php

namespace App\Services\Accounting\AccountingAccount;

use App\Models\Accounting\AccountingAccount;
use Illuminate\Support\Facades\DB;

class AccountingAccountService
{
    public function createAccount(array $data): AccountingAccount
    {
        if (!empty($data['parent_id'])) {
            $parent = AccountingAccount::findOrFail($data['parent_id']);
            $data['level'] = $parent->level + 1;
            $data['type']  = $parent->type; // El hijo hereda el tipo del padre (Activo, Pasivo, etc)
        } else {
            $data['level'] = 1;
        }

        return AccountingAccount::create($data);
    }

    public function updateAccount(AccountingAccount $account, array $data): bool
    {
        if (isset($data['parent_id']) && $data['parent_id'] != $account->parent_id) {
            $parent = AccountingAccount::find($data['parent_id']);
            $data['level'] = $parent ? $parent->level + 1 : 1;
        }

        return $account->update($data);
    }
}