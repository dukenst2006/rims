<?php

namespace Rims\Domain\Education\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EducationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
