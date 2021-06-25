<?php

namespace App\Http\Controllers;

use App\Http\HelperClass\HelperClass;
use App\Http\Requests\GenerateLinkRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\ShareLinkRequest;
use App\Image;
use App\Link;
use App\Share;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ImageController extends Controller
{
    public function index()
    {
        $totalJpgs = Image::where('mimetype', 'jpg')->orWhere('mimetype', 'jpeg')->count();
        $totalPngs = Image::where('mimetype', 'png')->count();
        $currentTime = Carbon::now();

        $searchKeyword = request()->searchKey;
        if ($searchKeyword) {
            $images = Image::where('filename', 'LIKE', "%{$searchKeyword}%")->get();
        } else {
            $images = Image::orderBy('id', 'DESC')->get();
        }
        return view('welcome', ['images' => $images, 'totalJpgs' => $totalJpgs, 'totalPngs' => $totalPngs, 'currentTime' => $currentTime]);
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
        return back()->with('success', 'Images uploaded successfully');
    }

    public function show(Image $image)
    {
        return view('show')->withImage($image);
    }

    public function edit(Image $image)
    {
        return view('edit')->withImage($image);
    }

    public function update(Request $request, Image $image)
    {
        $image->update($request->all());
        Session::flash('success', 'Image renamed successfully');
        return redirect('/');
    }

    public function generateLink(GenerateLinkRequest $request)
    {
        $imageId = $request->imageId;
        $link = $request->link;
        Link::create([
            'image_id' => $imageId,
            'link' => $link,
            'expire_at' => Carbon::now()->addMinutes(10), // Add 10 minutes to current time of a link generated as an expiration time of the the link
        ]);
        Session::flash('success', 'Image link generated successfully');
        return redirect('/');
    }

    public function reGenerateLink(GenerateLinkRequest $request)
    {

        $imageId = $request->imageId;
        $link = $request->link;

        $linkModel = Link::where('image_id', $imageId)->first();
        $linkModel->delete();

        Link::create([
            'image_id' => $imageId,
            'link' => $link,
            'expire_at' => Carbon::now()->addMinutes(10), // Add 10 minutes to current time of a link generated
        ]);
        Session::flash('success', 'Image link re-generated successfully');
        return redirect('/');
    }

    public function shareLink(ShareLinkRequest $request)
    {

        $linkId = $request->linkId;
        $share = Share::where('link_id', $linkId)->first();

        if ($share) {
            $currentNumberOfTime = $share->time;
            $share->update(['time' => $currentNumberOfTime + 1]);
            Session::flash('success', 'New share has been added');
            return redirect('/');
        }
        Share::create([
            'link_id' => $linkId,
            'time' => 1,
        ]);
        Session::flash('success', 'New share has been added');
        return redirect('/');
    }
}
