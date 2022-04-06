<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\CashIn;
use App\Models\CashOut;
use App\Models\Game;
use App\Models\Transaction;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class CardController extends Controller
{
    public function __construct(Request $request)
    {
        if(in_array($request->method(),["PATCH","POST"])){
            $request->validate([
                'cardCode' => $request->method() == "PATCH" ?'required|string': '',
                'gameId' => $request->method() == "PATCH" ? 'required|integer':''
            ]);
        }
    }

    public function patch(Request $request){

        //get card by card code
        $objectFromDB = Card::Where("code",$request->cardCode)->first();
        if($objectFromDB != null){

            //get price by game id
            $priceBygameId = Game::Where('id',$request->gameId)->first()->amount;
            if($priceBygameId == null){
                return response()->json(['erorr' =>'invalid game'],404);
            }
            // get totoal of cashin and payments from transactions table
            $cashIn = Transaction::Where('card_id',$objectFromDB->id)->Where('transaction_type','App\Models\CashIn')->sum('amount');
            $cashOut = Transaction::Where('card_id',$objectFromDB->id)->Where('transaction_type','App\Models\CashOut')->sum('amount');

            //get end balance for card
            $balance = ($cashIn - $cashOut);

            if($balance < $priceBygameId){
                //throw
                return response()->json(['erorr' =>'sorry. You do not have balance'],404);
            }else{
                $request = CashOut::create([
                    'name' => 'cash out',
                    'amount' => $priceBygameId,
                    'card_id' => $objectFromDB->id,
                    'description' => $request->description?$request->description:''
                ]);

                $request->transactions()->create([
                    'name' => $request->name,
                    'card_id' => $objectFromDB->id,
                    'amount' => $priceBygameId
                ]);
            }
        }else{
            return response()->json(['erorr' =>'invalid card'],404);
        }

        return response()->json(['success'=>'done successfully']);
    }
}
