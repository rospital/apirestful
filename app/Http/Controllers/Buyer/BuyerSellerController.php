<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.seller')
            ->get()
            ->pluck('product.seller')
            ->unique('id')
            ->values();


            /*  en pluck no le puedo poner directamente seller porque llego a seller por producto
                unique lo que hace es no mostrar repetidos, y para asegurarme de eso me fijo que el id sea unico
                values es para el caso de que hayan sellers eliminados de la coleccion, solo me muestra los que existan
            */

        //dd($sellers);
                
        return $this->showAll($sellers);
    }

}
