<?php

namespace Rims\Http\Account\Controllers\Portfolio;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Rims\Domain\Portfolios\Models\PortfolioUpload;
use Rims\Domain\Portfolios\Models\Portfolio;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Portfolios\Requests\PortfolioUploadsStoreRequest;
use Rims\Domain\Portfolios\Resources\PortfolioUploadCollection;
use Rims\Domain\Portfolios\Resources\PortfolioUploadResource;

class PortfolioUploadController extends Controller
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * PortfolioUploadController constructor.
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return PortfolioUploadCollection
     */
    public function index(Portfolio $portfolio)
    {
        return new PortfolioUploadCollection($portfolio->uploads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @return PortfolioUploadResource
     */
    public function store(PortfolioUploadsStoreRequest $request, Portfolio $portfolio)
    {

        // catch uploaded file
        $uploadedFile = $request->file('file');

        // store file in storage
        $upload = $this->storeUpload($portfolio, $uploadedFile);

        // resize and temporarily store image
        $processedImage = $this->imageManager->make($uploadedFile->getPathName())
            ->fit(500, 375, function ($c) {
                $c->aspectRatio();
            })->encode('png')
            ->save($path = config('image.path.portfolio.absolute') . '/' . uniqid(true) . '.png');


        // new filename
        $filename = ($upload->filename . '.png');

        //store original in local storage
        $path = Storage::disk('public')->putFileAs(
            "users/{$request->user()->id}/portfolios/{$portfolio->identifier}",
            new File($path),
            $filename
        );

        // delete image
        $processedImage->destroy();

        // update upload path
        $upload->update([
            'filename' => $filename,
            'path' => $path,
            'type' => 'image/png'
        ]);

        return new PortfolioUploadResource($upload);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioUpload $upload
     * @return \Illuminate\Http\Response
     */
    public function show(Portfolio $portfolio, PortfolioUpload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioUpload $upload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Portfolio $portfolio, PortfolioUpload $upload)
    {
        $upload->update($request->only('preview'));

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rims\Domain\Portfolios\Models\Portfolio $portfolio
     * @param  \Rims\Domain\Portfolios\Models\PortfolioUpload $upload
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Portfolio $portfolio, PortfolioUpload $upload)
    {
        $upload->delete();

        Storage::disk('public')->delete($upload->fullPath);

        return response()->json(null, 204);
    }

    /**
     * Store's uploaded file to storage.
     *
     * @param Portfolio $portfolio
     * @param UploadedFile $uploadedFile
     * @return PortfolioUpload
     */
    protected function storeUpload(Portfolio $portfolio, UploadedFile $uploadedFile)
    {
        //new file upload init
        $upload = new PortfolioUpload;

        //fill upload
        $upload->fill([
            'filename' => $this->generateFilename($uploadedFile),
            'path' => $uploadedFile->getPathname(), // temporary path
            'size' => $uploadedFile->getSize(),
            'type' => $uploadedFile->getClientMimeType(),
            'preview' => true
        ]);

        //associate upload
        $upload->portfolio()->associate($portfolio);
        $upload->uploadable()->associate($portfolio->fileable);

        //save
        $upload->save();

        return $upload;
    }

    /**
     * Get's original filename from client.
     *
     * @param UploadedFile $uploadedFile
     * @return null|string
     */
    protected function generateFilename(UploadedFile $uploadedFile)
    {
        $extension = $uploadedFile->getClientOriginalExtension();

        return head(explode(".{$extension}", $uploadedFile->getClientOriginalName()));
    }
}
