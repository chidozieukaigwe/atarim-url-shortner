<?php

namespace App\Services\Api\V1;

use App\Models\Link;
use Vinkla\Hashids\Facades\Hashids;

class LinkService
{

    protected $basePath;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->basePath = url("/") . "/";
    }

    public function generateShortUrl($originalUrl): Link
    {
        $existingUrl = Link::where('original_url', $originalUrl)->first();

        if ($existingUrl) {
            return $this->basePath . $existingUrl->short_code;
        }

        $link = Link::create([
            'original_url' => $originalUrl,
        ]);

        // @todo link this is readme file - https://github.com/vinkla/laravel-hashids

        $link->short_code = Hashids::encode($link->id);
        $link->save();

        return $link;
    }

    public function retrieveOriginalUrl($shortCode)
    {
        $linkId = Hashids::decode($shortCode);
        $link = Link::find($linkId[0]);
        return $link;
    }
}
