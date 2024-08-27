<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class nav extends Component
{
    public $items; // not need to pass it to view
    public $active;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->items = config("nav"); // now can access to items in nav.blade.php and access to nav array in config('nav')
        // $this->active = Route::currentRouteName(); // return the route name, for seclet the active icon

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nav');
    }
}
