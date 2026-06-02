<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BukuCard extends Component
{
    // Deklarasikan property
    public $buku;
    public $showActions;

    /**
     * Create a new component instance.
     */
    public function __construct($buku, $showActions = true)
    {
        // Masukkan data dari pemanggilan ke dalam property component
        $this->buku = $buku;
        $this->showActions = $showActions;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buku-card');
    }
}