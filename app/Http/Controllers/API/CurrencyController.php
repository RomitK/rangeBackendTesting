<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Currency
};
use App\Http\Resources\{
    CurrencyResource
};

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();

        // Transform each currency into a key-value pair using the CurrencyResource
        $currencyRates = $currencies->mapWithKeys(function($currency) {
            return [$currency->name => $currency->value];
        });
        return $this->success('rate',$currencyRates, 200);
    }
}
