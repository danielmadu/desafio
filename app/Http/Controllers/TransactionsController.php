<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionsController extends Controller
{
    public function show()
    {

    }

    public function create()
    {
        return view('transactions.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'payer' => ['exists:users|email', Rule::requiredIf($request->isJson())],
            'payee' => 'exists:users|email|required',
        ]);

        /** @var User $payer */
        $payer = $request->isJson() ? User::query()->where('email', $request->input('payer'))->first();
        /** @var User $payee */
        $payee = User::query()->where('email', $request->input('payee'))->first();

        $transaction = new Transaction();
        $transaction->payer = $payer->id;
        $transaction->payee = $payee->id;
        $transaction->save();

        if ($request->isJson()) {
            return response()->status(200)->json(['message' => 'Transaction successfully created']);
        }

        return redirect()->route('transaction.save');
    }
}
