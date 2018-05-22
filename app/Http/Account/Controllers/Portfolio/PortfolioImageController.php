<?php

namespace Rims\Http\Account\Controllers\Portfolio;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Rims\Domain\Portfolios\Models\Portfolio;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Portfolios\Requests\PortfolioImageStoreRequest;
use Rims\Domain\Portfolios\Resources\PortfolioResource;

class PortfolioImageController extends Controller
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * PortfolioImageController constructor.
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        // todo: retrieve images from portfolio folder
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PortfolioImageStoreRequest $request
     * @param Portfolio $portfolio
     * @return PortfolioResource
     */
    public function store(PortfolioImageStoreRequest $request, Portfolio $portfolio)
    {
        $image = $request->file('image');

        $processedImage = $this->imageManager->make($image->getPathName())
            ->fit(500, 375, function ($c) {
                $c->aspectRatio();
            })->encode('png')
            ->save($path = config('image.path.portfolio.absolute') . '/' . uniqid(true) . '.png');

        // save to storage
        $path = Storage::disk('public')->putFileAs(
            "users/{$request->user()->id}/portfolios/{$portfolio->identifier}",
            new File($path),
            $image = uniqid(true) . '.png'
        );

        // destroy processed
        $processedImage->destroy();

        // update image
        $portfolio->update([
            'image' => $image
        ]);

        return new PortfolioResource($portfolio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        // todo: get and update portfolio image in request if exists in file storage
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Portfolio $portfolio)
    {
        // todo: get and delete portfolio image from db (if set) and file storage
    }
}
