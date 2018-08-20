<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17.08.2018
 * Time: 15:29
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Models\CartSetProduct as Items;

class CartSetProduct extends Controller
{
    public function lastItem() {
        $last_update = Items::whereIn('print_status_id', [78, 68, 69, 67])->orderBy('updated_at', 'desc')->pluck('id')->first() ?: 0;
        $last_add = 0;
        foreach (Items::orderBy('created_at', 'desc')->get() as $item) {
            if ($item->cart->order) {
                $last_add = $item->id;
                break;
            }
        }
        return response()->json(['last_update' => $last_update, 'last_add' => $last_add]);
    }
}