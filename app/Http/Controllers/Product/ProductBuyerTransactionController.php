<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class ProductBuyerTransactionController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        $this->validate($request, $rules);

        if($buyer->id == $product->seller_id){
            return $this->errorResponse('El comprador debe ser diferente al vendedor', 409);
        }

        if(!$buyer->esVerificado()){
            return $this->errorResponse('El comprador debe ser un usuario verificado', 409);
        }

        if(!$product->seller->esVerificado()){
            return $this->errorResponse('El vendedor debe ser un usuario verificado', 409);
        }

        if(!$product->estaDisponible()){
            return $this->errorResponse('El producto para esta transaccion no esta disponible', 409);
        }

        if($product->quantity < $request->quantity){
            return $this->errorResponse('El producto no tiene la cantidada disponible para esta transaccion no esta disponible', 409);
        }

        /*
        Uso de DB::transaction
        Esto es para que cuando este realizando una operacion en la BD 
        No haya otra persona realizando lo mismo, por ejemplo
        Con esto me aseguro que se haga una por vez
        Por ejemplo. Si la cantidad la estoy bajando de 2 partes distintas, 
        tengo que tener un control de que siempre existan  productos
        */
        return DB::transaction(function() use ($request, $product, $buyer){
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction);
        });
    }
}