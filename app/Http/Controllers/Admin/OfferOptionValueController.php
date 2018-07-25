<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop\Offer;
use Illuminate\Http\Request;

class OfferOptionValueController extends Controller
{
    protected $rules = [
        'active' => 'boolean',
    ];

    /**
     * @param  Request $request
     * @param  integer  $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $data = $request->only(['active']);

        if (count($data) == 0) {
            return;
        }

        Offer::findOrFail($id)->update($data);
    }
}
