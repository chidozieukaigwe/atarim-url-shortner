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

    public function generateShortUrl($originalUrl): array
    {
        // $existingUrl = Link::where('original_url', $originalUrl)->first();

        // if ($existingUrl) {
        //     return $this->basePath . $existingUrl->short_code;
        // }

        $link = Link::create([
            'original_url' => $originalUrl,
        ]);

        // @todo link this is readme file - https://github.com/vinkla/laravel-hashids

        $link->short_code = Hashids::encode($link->id);
        $link->save();

        $shortUrl = $this->basePath . $link->short_code;

        return [
            'original_url' => $link->original_url,
            'short_code' => $link->short_code,
            'short_url' => $shortUrl,
        ];
    }

    public function retrieveOriginalUrl($shortCode)
    {
        $linkId = Hashids::decode($shortCode);
        $link = Link::find($linkId[0]);
        return $link->original_url;
    }
}
