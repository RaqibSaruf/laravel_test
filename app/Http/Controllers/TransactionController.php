<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TransactionController extends Controller
{
    public function __construct(private TransactionRepository $repository, private TransactionService $service)
    {
    }

    public function index(Request $request)
    {
        return view('transactions.list')
            ->with([
                'transactions' => $this->repository->paginate($request->query->all()),
                'currentBalance' => User::select('balance')->findOrFail(Auth::id())->balance
            ]);
    }


    public function deposit(Request $request)
    {
        return view('transactions.deposit')
            ->with([
                'transactions' => $this->repository->paginate([
                    ...$request->query->all(),
                    'transaction_type' => config('common.transactionType.deposit')
                ]),
                'currentBalance' => User::select('balance')->findOrFail(Auth::id())->balance
            ]);
    }

    public function addDeposit()
    {
        return view('transactions.create')
            ->with([
                'transactionType' => config('common.transactionType.deposit'),
                'currentBalance' => User::select('balance')->findOrFail(Auth::id())->balance
            ]);
    }

    public function withdrawal(Request $request)
    {
        return view('transactions.withdrawal')
            ->with([
                'transactions' => $this->repository->paginate([
                    ...$request->query->all(),
                    'transaction_type' => config('common.transactionType.withdrawal')
                ]),
                'currentBalance' => User::select('balance')->findOrFail(Auth::id())->balance
            ]);
    }

    public function addWithdrawal()
    {
        return view('transactions.create')
            ->with([
                'transactionType' => config('common.transactionType.withdrawal'),
                'currentBalance' => User::select('balance')->findOrFail(Auth::id())->balance
            ]);
    }

    public function store(TransactionRequest $request)
    {
        $this->service->save($request);

        return Redirect::route('transaction.' . array_flip(config('common.transactionType'))[$request->validated('transaction_type')] . '.list');
    }
}