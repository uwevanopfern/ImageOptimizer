<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Http\HelperClass\HelperClass;
use App\Http\Requests\ImageRequest;
use App\Http\Resources\Image as ImageResource;
use App\Http\Resources\ImageCollection;
use App\Image;
use App\Link;
use App\Share;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::orderBy('id', 'DESC')->get();
        return new ImageCollection($images);
    }

    public function store(ImageRequest $request)
    {
        if ($request->hasfile('images')) {
            $files = $request->file('images');

            foreach ($files as $file) {

                $fileSize = $file->getSize();
                $mimeType = $file->getClientOriginalExtension();
                $name = $file->getClientOriginalName();

                $path = $file->storeAs('uploads', $name, 'public');

                Image::create([
                    'filename' => $name,
                    'filesize' => HelperClass::convertToReadableSize($file->getSize()),
                    'filepath' => '/storage/' . $path,
                    'mimetype' => $mimeType,
                ]);
            }
        }

        return response([
            'success' => true,
            'message' => 'Images uploaded successfully',

        ], Response::HTTP_CREATED);
    }

    public function show(Image $image)
    {
        return new ImageResource($image);
    }

    public function update(Request $request, Image $image)
    {
        $validatedData = $request->validate([
            'filename' => 'required',
        ]);

        $image->update(['filename' => $validatedData['filename']]);
        return response([
            'success' => true,
            'message' => 'Image renamed successfully',

        ], Response::HTTP_OK);
    }

    public function generateLink($imageId)
    {
        $image = Image::where('id', $imageId)->first();
        if ($image) {

            $imageId = $image->id;
            $linkObject = Link::where('image_id', $imageId)->first();

            if ($linkObject) {
                $currentTime = Carbon::now();
                $linkExpireAt = $linkObject->expire_at;
                if ($currentTime > $linkExpireAt) {
                    return response([
                        'success' => false,
                        'message' => 'Link expired, Re generate new link',

                    ], Response::HTTP_BAD_REQUEST);
                } else {
                    return response([
                        'success' => false,
                        'message' => 'Link already exists',

                    ], Response::HTTP_BAD_REQUEST);
                }
            }
            $link = asset($image->filepath);
            $generateLink = Link::create([
                'image_id' => $imageId,
                'link' => $link,
                'expire_at' => Carbon::now()->addMinutes(10),
            ]);

            if ($generateLink) {
                return response([
                    'success' => true,
                    'message' => 'Image link generated successfully',

                ], Response::HTTP_OK);

            }
            return response([
                'success' => false,
                'message' => 'Failed to generate image link',

            ], Response::HTTP_BAD_REQUEST);

        } else {
            return response([
                'success' => false,
                'message' => 'Image not found',

            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function reGenerateLink($imageId)
    {
        $image = Image::where('id', $imageId)->first();
        if ($image) {
            $linkModel = Link::where('image_id', $imageId)->first();
            $linkModel->delete();

            $link = asset($image->filepath);

            $reGenerateLink = Link::create([
                'image_id' => $imageId,
                'link' => $link,
                'expire_at' => Carbon::now()->addMinutes(10),
            ]);

            if ($reGenerateLink) {
                return response([
                    'success' => true,
                    'message' => 'Image link re-generated successfully',

                ], Response::HTTP_OK);
            }
            return response([
                'success' => false,
                'message' => 'Failed to re-generate image link',

            ], Response::HTTP_BAD_REQUEST);
        } else {
            return response([
                'success' => false,
                'message' => 'Image not found',

            ], Response::HTTP_NOT_FOUND);

        }
    }

    public function shareLink($linkId)
    {
        $link = Link::find($linkId);

        $share = Share::where('link_id', $linkId)->first();

        //Check if link is expired before it gets shared
        if ($link) {
            $currentTime = Carbon::now();
            $linkExpireAt = $link->expire_at;
            if ($currentTime > $linkExpireAt) {
                return response([
                    'success' => false,
                    'message' => 'Link expired, Generate new link',

                ], Response::HTTP_BAD_REQUEST);
            }

            if ($share) {
                $currentNumberOfTime = $share->time;
                $share->update(['time' => $currentNumberOfTime + 1]);
                return response([
                    'success' => true,
                    'message' => 'New share has been added',

                ], Response::HTTP_OK);
            }
            $shareLink = Share::create([
                'link_id' => $linkId,
                'time' => 1,
            ]);

            if ($shareLink) {
                return response([
                    'success' => true,
                    'message' => 'New share has been added',

                ], Response::HTTP_OK);
            }
            return response([
                'success' => false,
                'message' => 'Failed to share image link',

            ], Response::HTTP_BAD_REQUEST);

        } else {
            return response([
                'success' => false,
                'message' => 'Image link not found',

            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function statistics()
    {
        $totalJpgs = Image::where('mimetype', 'jpg')->orWhere('mimetype', 'jpeg')->count();
        $totalPngs = Image::where('mimetype', 'png')->count();

        return response([
            'totalJpgs' => $totalJpgs,
            'totalPngs' => $totalPngs,

        ], Response::HTTP_OK);
    }

    public function searchImageByName(Request $request)
    {
        $validatedData = $request->validate([
            'searchKey' => 'required',
        ]);

        $searchKeyword = $validatedData['searchKey'];
        $images = Image::where('filename', 'LIKE', "%{$searchKeyword}%")->get();
        return new ImageCollection($images);
    }
}
