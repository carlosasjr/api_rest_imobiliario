<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    //determinar quais campos serão retornados
     /*   return [
          'name'  => $this->name,
          'price' => $this->price,

        ];*/

      return $this->resource->toArray();
    }
}
