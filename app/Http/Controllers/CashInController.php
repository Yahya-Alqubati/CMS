<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use Illuminate\Http\Request;

class CashInController extends Controller
{

    public function __construct(Request $request)
    {
        if(in_array($request->method(),["POST"])){
       $request->validate([
                'name' => $request->method() == "POST" ?'required|string|max:255|min:2': '',
                'cardId' => $request->method() == "POST" ? 'required':'',
                'amount' => $request->method() == "POST" ? 'required':'',
            ]);
        }

    }

    public function store(Request $request)
    {
        $cardId = $request->cardId;
        $request = CashIn::create([
            'name' => $request->name,
            'amount' => $request->amount,
            'card_id' => $cardId,
            'description' => $request->description?$request->description:''
        ]);

        $request->transactions()->create([
            'name' => $request->name,
            'card_id' => $cardId,
            'amount' => $request->amount
        ]);

        return response()->json($request);
    }
}
