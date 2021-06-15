<?php

namespace App\Http\Controllers;

use App\Events\TransactionCreated;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Authorizer;
use App\Services\Authorizer\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionsController extends Controller
{
    public function list()
    {
        $user = Auth::user();
        dd($user);
        $sended = Auth::user()->sended;
        $received = Auth::user()->received;
        return view('transactions.list', [
            'sended' => $sended,
            'received' => $received,
        ]);
    }

    public function sended()
    {
        $sended = Auth::user()->sended;

        return response()->json($sended);
    }

    public function received()
    {
        $received = Auth::user()->received;

        return response()->json($received);
    }

    public function create()
    {
        if (Auth::user()->is_seller) {
            return redirect()->route('dashboard');
        }
        return view('transactions.create');
    }

    /**
     * @throws GuzzleException
     */
    public function save(Request $request)
    {
        if (Auth::user()->is_seller) {
            if ($request->isJson()) {
                return response()->status(403);
            }
            return redirect()->route('dashboard');
        }

        $client = new Client();
        $authorizer = new Authorizer($client);

        if (!$authorizer->check()) {
            if ($request->isJson()) {
                return response()->status(401)->json(['message' => $authorizer->getMessage()]);
            }

            return redirect()->route('transactions.list')->withErrors($authorizer->getMessage());
        }

        /** @var User $user */
        $user = Auth::user();
        $request->validate([
            'payer' => ['exists:users,email', 'email', Rule::requiredIf($request->isJson())],
            'payee' => ['exists:users,email','email', 'required'],
            'value' => 'required|max:'. $user->wallet->total_amount,
        ]);

        /** @var User $payer */
        $payer = $request->isJson() ? User::query()->where('email', $request->input('payer'))->first() : $user;
        /** @var User $payee */
        $payee = User::query()->where('email', $request->input('payee'))->first();

        $transaction = new Transaction();
        $transaction->payer = $payer->id;
        $transaction->payee = $payee->id;
        $transaction->value = (float)$request->input('value');
        $transaction->save();

        TransactionCreated::dispatch($transaction);

        if ($request->isJson()) {
            return response()->json([
                'message' => 'Transaction successfully created',
                'data'    => [
                    'transaction' => $transaction,
                ]
            ]);
        }

        return redirect()->route('transactions.list')->with(['message' => 'Transaction successfully created']);
    }
}
