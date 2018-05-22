<?php

namespace Rims\Http\Account\Controllers\Avatar;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Rims\App\Controllers\Controller;
use Rims\Domain\Account\Requests\AvatarStoreRequest;
use Rims\Domain\Users\Resources\UserResource;

class AvatarUploadController extends Controller
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * AvatarUploadController constructor.
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return UserResource
     */
    public function store(AvatarStoreRequest $request)
    {
        $processedImage = $this->imageManager->make($request->file('image')->getPathName())
            ->fit(100, 75, function ($c) {
                $c->aspectRatio();
            })->encode('png')
            ->save($path = config('image.path.avatar.absolute') . '/' . uniqid(true) . '.png');

        //store original in local storage
        $path = Storage::disk('public')->putFileAs(
            "users/{$request->user()->id}/avatars",
            new File($path),
            $avatar = uniqid(true) . '.png'
        );

        // update user avatar
        $request->user()->update([
            'avatar' => $avatar
        ]);

        // delete processed image
        $processedImage->destroy();

        return new UserResource($request->user());
    }
}
