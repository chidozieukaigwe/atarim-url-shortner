<?php

namespace App\Services\Api\V1;

use App\Exceptions\FailedShortUrlException;
use App\Exceptions\FailedToShortUrlException;
use App\Models\Link;
use Exception;
use Illuminate\Support\Facades\Log;
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
        try {
            $existingUrl = Link::where('original_url', $originalUrl)->first();

            if ($existingUrl) {
                return $existingUrl;
            }

            $link = Link::create([
                'original_url' => $originalUrl,
            ]);

            // @todo link this is readme file - https://github.com/vinkla/laravel-hashids

            $link->short_code = Hashids::encode($link->id);
            $link->save();

            Log::info('Link successfully shortened: ', ['link_details' => $link]);
            return $link;
        } catch (Exception $exception) {
            // @todo add debug
            Log::error('Error shortening the url: ', ['exception' => $exception]);
            throw new FailedToShortUrlException();
        }
    }

    public function retrieveOriginalUrl($shortCode)
    {
        // @notes: Hashids returns an array
        $linkId = Hashids::decode($shortCode);
        $link = Link::find($linkId[0]);
        return $link;
    }
}
