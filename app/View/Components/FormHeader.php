<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormHeader extends Component
{
    public function __construct(
        public string $title,
        public ?string $subtitle = null,
        public ?string $backRoute = null,
    ) {}

    public function render()
    {
        return view('components.form-header');
    }
}
