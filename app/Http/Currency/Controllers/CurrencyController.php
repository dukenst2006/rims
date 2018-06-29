<?php

namespace Rims\Http\Currency\Controllers;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Currency\Models\Currency;
use Rims\Domain\Currency\Resources\CurrencyCollection;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CurrencyCollection
     */
    public function index()
    {
        $currencies = Currency::where('usable', true)->orderBy('cc', 'asc')->get();

        return new CurrencyCollection($currencies);
    }
}
