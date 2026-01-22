<?php 

/* namespace App\Filters\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ClientBusinessFilter implements FilterInterface
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        return $query->whereHas('pointsOfSale', function ($q) {
            $q->where('business_type_id', $this->request->input('business_type'));
        });
    }
} */

// ELiminar filtro mal colocado.
