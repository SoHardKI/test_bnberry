<?php

namespace App\Http\Controllers;

use App\Jobs\DeletePicture;
use App\Jobs\DownloadPicture;
use App\Models\Picture;
use App\Services\StringWorkerService\StringWorkerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class PictureController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('picture.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url',
            'lifetime' => 'integer|nullable',
        ]);

        $input_data = $request->input();
        $short_url = StringWorkerService::generateRandomString();
        $input_data['short_url'] = URL::to('/') . '/' . $short_url;
        Picture::create($input_data);
        DownloadPicture::dispatch($request->get('url'), $short_url);
        if ($request->get('lifetime')) {
            $job = (new DeletePicture($input_data['short_url'], $short_url))
                ->delay(60 * $request->get('lifetime'));
            $this->dispatch($job);
        }

        return view('picture.show_link', ['link' => $input_data['short_url']]);
    }

    /**
     * @param Request $request
     * @param string $short_url
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function getPicture(Request $request, string $short_url)
    {
        $picture = Picture::where('short_url', URL::to('/') . '/' . $short_url)->first();

        if (!$picture) {
            return redirect()->route('picture.create')->with('warning', 'Incorrect link or picture has been removed.');
        } elseif (!file_exists(public_path() . '/img/' . $short_url . '.jpg')) {
            return redirect()->route('picture.create')->with('warning', 'Image has not been downloaded yet.');
        }

        return Image::make(public_path() . '/img/' . $short_url . '.jpg')->response();
    }
}
