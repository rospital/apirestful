<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
        // De muchos a muchos

        // puedo usar sync, attach, syncWithoutDetaching

        // sync
        //$product->categories()->sync([$category->id]);
        // sync no sirve porque lo que hace es sustituir las existentes por esa

        // attach
        //$product->categories()->attach([$category->id]);
        // funciona bien, agrega la nueva categoria, 
        // pero el problema es que la agrega cuantas veces quieramos

        //syncWithoutDetaching
        $product->categories()->syncWithoutDetaching([$category->id]);
        // agrega las nuevas sin eliminar las anteriores

        return $this->showAll($product->categories);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories()->find($category->id)){
            return $this->errorResponse('La categoria especificada no es una categoria de este producto', 404);
        }

        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);
    }
}
