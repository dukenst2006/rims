<?php

namespace Rims\Domain\Portfolios\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PortfolioUploadCollection extends ResourceCollection
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
