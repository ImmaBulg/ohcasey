<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Shop\Product;

class TransactionCategoryController extends Controller
{
    protected $rules = [
        'name' => 'required'
    ];

    /**
     *
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $perPage = 50;
        $query = TransactionCategory::orderBy('id', 'desc');

        $transactionCategories = $query->paginate($perPage);
        if ($transactionCategories->currentPage() > $transactionCategories->lastPage()) {
            $transactionCategories = $query->paginate($perPage, ['*'], 'page', $transactionCategories->lastPage());
        }

        return $transactionCategories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return TransactionCategory
     */
    public function store(Request $request)
    {
        $transactionCategory = new TransactionCategory();
        $this->validate($request, $this->rules);
        $transactionCategory->fill($request->all())->save();
        return $transactionCategory;
    }

    /**
     *
     * @param  int $id
     * @return array
     */
    public function edit($id)
    {
        return ['form' => TransactionCategory::findOrFail($id)];
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return TransactionCategory
     */
    public function update(Request $request, $id)
    {
        $transactionCategory = TransactionCategory::findOrFail($id);
        $this->validate($request, $this->rules);
        $transactionCategory->fill($request->all())->save();
        return $transactionCategory;
    }

    /**
     * @param  int $id
     * @return TransactionCategory
     */
    public function destroy($id)
    {
        $transactionCategory = TransactionCategory::findOrFail($id);
        $transactionCategory->delete();
        return $transactionCategory;
    }
}
