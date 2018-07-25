<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\TransactionType;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $rules = [
        'amount' => 'required|numeric',
        'transaction_type_id' => 'required'
    ];

    /**
     * @param Request $request
     * @return array
     */
    public function report(Request $request)
    {
        setlocale(LC_TIME, 'ru_RU.UTF-8');
        Carbon::setLocale('ru');
        $year = Carbon::create($request->get('year'))->startOfYear();
        $intervals = [];
        for ($i = 0; $i < 12; $i++) {
            $intervals[] = [
                'start' => $year->copy()->startOfMonth(),
                'end'   => $year->copy()->endOfMonth(),
                'name'  => $year->formatLocalized('%B')
            ];
            $year->addMonth();
        }

        $year->subMonth();

        $intervals[] = [
            'start' => $year->copy()->startOfYear(),
            'end'   => $year->copy()->endOfYear(),
            'name'  => 'Итого ' . $year->year . ' факт',
        ];

        // все статьи доход - объеденяем в одну статью "выручка"
        $report = [
            'intervals' => [],
            'income' => [
                [
                    'name'  => 'Выручка',
                    'types' => [
                        [
                            'name' => 'Выручка',
                            'intervals' => [],
                        ],
                    ],
                    'totals' => [],
                ],
            ],
            'cost' => [

            ],
            'total' => [
                'intervals' => [

                ],
            ],
        ];

        /** @var Collection|TransactionType[] $incomeTransactionTypes */
        $incomeTransactionTypes = TransactionType::join('transaction_categories', function (JoinClause $join) {
            $join->on('transaction_categories.id', '=', 'transaction_types.transaction_category_id')
                ->where('transaction_categories.is_incoming', '=', true);
        })->select('transaction_types.*')->get();

        /** @var Collection|TransactionType[] $costTransactionCategories */
        $costTransactionCategories = TransactionCategory::where('is_incoming', 'false')
            ->orderBy('name')
            ->with('types')
            ->get();

        foreach ($intervals as $interval) {
            $report['intervals'][] = [
                'name'  => $interval['name']
            ];

            $totalIncomeSum = floatval(Transaction::whereBetween('datetime', [$interval['start'], $interval['end']])
                ->whereIn('transaction_type_id', $incomeTransactionTypes->pluck('id'))
                ->sum('amount') ?: 0);

            $report['income'][0]['types'][0]['intervals'][] = ['value' => $totalIncomeSum];

            $report['income'][0]['totals'][] = ['value' => $totalIncomeSum];

            foreach ($costTransactionCategories as $category) {
                if (! isset($report['cost'][$category->id])) {
                    if (! isset($report['cost'][$category->id])) {
                        $report['cost'][$category->id] = [
                            'name'   => $category->name,
                            'types'  => [],
                            'totals' => [],
                        ];
                    }
                }

                $totalCategorySum  = 0.0;
                $typesReport = &$report['cost'][$category->id]['types'];
                foreach ($category->types as $costType) {
                    if (! isset($typesReport[$costType->id])) {
                        $typesReport[$costType->id] = [
                            'name'      => $costType->name,
                            'intervals' => [],
                        ];
                    }

                    $amountSum = floatval(Transaction::whereBetween('datetime', [$interval['start'], $interval['end']])
                        ->where('transaction_type_id', $costType->id)
                        ->sum('amount') ?: 0);

                    $totalCategorySum += $amountSum;

                    $typesReport[$costType->id]['intervals'][] = [
                        'value' => $amountSum
                    ];
                }

                $report['cost'][$category->id]['totals'][] = ['value' => $totalCategorySum];
            }
        }

        foreach ($intervals as $index => $interval) {
            $total = 0;
            $total = array_reduce($report['income'], function ($total, $income) use ($index) {
                return $total + (isset($income['totals'][$index]['value']) ? $income['totals'][$index]['value'] : 0);
            }, $total);
            $total = array_reduce($report['cost'], function ($total, $income) use ($index) {
                return $total - (isset($income['totals'][$index]['value']) ? $income['totals'][$index]['value'] : 0);
            }, $total);
            $report['total']['intervals'][] = ['value' => $total];
        }

        return $report;
    }

    /**
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $perPage = 50;

        /** @var Builder $query */
        $query = Transaction::orderBy('id', 'desc')->with([
            'order',
            'payment',
            'type',
        ]);

        if ($request->has('transaction_type_id')) {
            $query->where('transaction_type_id', intval($request->get('transaction_type_id')));
        }

        if ($request->has('order_id')) {
            $query->where('order_id', intval($request->get('order_id')));
        }

        if ($request->has('payment_id')) {
            $query->where('payment_id', intval($request->get('payment_id')));
        }

        if ($request->has('amount')) {
            $query->where('amount', floatval($request->get('amount')));
        }

        $transactions = $query->paginate($perPage);

        if ($transactions->currentPage() > $transactions->lastPage()) {
            $transactions = $query->paginate($perPage, ['*'], 'page', $transactions->lastPage());
        }

        $transactions->appends($request->except('page'));

        return $transactions;
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Transaction
     */
    public function store(Request $request)
    {
        $transaction = new Transaction();
        $this->validate($request, $this->rules);
        $transaction->fill($request->all())->save();
        return $transaction;
    }

    /**
     *
     * @param  int $id
     * @return array
     */
    public function edit($id)
    {
        return ['form' => Transaction::findOrFail($id)];
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return Transaction
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->validate($request, $this->rules);
        $transaction->fill($request->all())->save();
        return $transaction;
    }

    /**
     * @param  int $id
     * @return Transaction
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return $transaction;
    }
}
