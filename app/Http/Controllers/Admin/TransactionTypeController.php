<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Shop\Product;

class TransactionTypeController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'transaction_category_id' => 'required|numeric',
    ];

    /**
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $perPage = 50;
        $query = TransactionType::orderBy('id', 'desc')->with(['category']);

        $transactionTypes = $query->paginate($perPage);
        if ($transactionTypes->currentPage() > $transactionTypes->lastPage()) {
            $transactionTypes = $query->paginate($perPage, ['*'], 'page', $transactionTypes->lastPage());
        }

        return $transactionTypes;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return TransactionType
     */
    public function store(Request $request)
    {
        $transactionType = new TransactionType();
        $this->validate($request, $this->rules);
        $transactionType->fill($request->all())->save();
        return $transactionType;
    }

    /**
     * @param  int $id
     * @return array
     */
    public function edit($id)
    {
        return ['form' => TransactionType::findOrFail($id)];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return TransactionType
     */
    public function update(Request $request, $id)
    {
        $transactionType = TransactionType::findOrFail($id);
        $this->validate($request, $this->rules);
        $transactionType->fill($request->all())->save();
        return $transactionType;
    }

    /**
     * @param  int $id
     * @return TransactionType
     */
    public function destroy($id)
    {
        $transactionType = TransactionType::findOrFail($id);
        $transactionType->delete();
        return $transactionType;
    }
}
