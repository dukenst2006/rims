<?php

namespace Rims\Http\Admin\Controllers\Currency;

use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Currency\Models\Currency;

class CurrencyStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \Rims\Domain\Currency\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $status = $currency->usable == true ? false : true;

        $currency->update([
            'usable' => $status
        ]);

        $message = $status == true ? 'activated' : 'disabled';

        return back()->withSuccess("{$currency->name} is {$message}.");
    }
}
