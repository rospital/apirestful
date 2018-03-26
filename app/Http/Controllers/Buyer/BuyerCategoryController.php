<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse()
            ->unique('id')
            ->values();
                
        /* 
            utilice collapse porque cada producto tiene una lista de categorias, entonces el resultado eran varias listaas. Al usar eso lo que hace es une todos los elementos de las varias listas
        */    

        return $this->showAll($categories);
    }
}
