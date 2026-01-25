<?php 

namespace App\Filters\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Contracts\FilterInterface;

class ClientStateFilter implements FilterInterface 
{
    public function __construct(protected Request $request) {}

    public function apply(Builder $query): Builder 
    {
        $value = $this->request->input('state');
        return $value ? $query->where('state_id', $value) : $query;
    }
}