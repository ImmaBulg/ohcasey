<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class EcommerceController extends Controller
{
    /**
     * Main Vue.js view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showView()
    {
        return view('admin.vue_ecommerce');
    }
}
