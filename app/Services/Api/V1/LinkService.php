<?php

namespace App\Services\Api\V1;

use App\Exceptions\FailedToShortUrlException;
use App\Models\Link;
use App\Repositories\LinkRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class LinkService
{
    protected $linkRepository;

    public function __construct(LinkRepositoryInterface $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    private function createAHashShortCode(string $linkId)
    {
        try {
            return Hashids::encode($linkId);
        } catch (Exception $th) {
            // @todo add a different exception error
            throw new FailedToShortUrlException();
        }
    }

    private function decodeAHashShortCode(string $linkId)
    {
        try {
            return Hashids::decode($linkId)[0];
        } catch (Exception $th) {
            // @todo add a different exception error
            throw new FailedToShortUrlException();
        }
    }

    public function generateShortUrl($originalUrl): Link
    {
        try {

            $existingUrl = $this->linkRepository->where('original_url', null, $originalUrl);

            if ($existingUrl) {
                return $existingUrl;
            }

            $link = $this->linkRepository->create([
                'original_url' => $originalUrl
            ]);

            // @todo link this is readme file - https://github.com/vinkla/laravel-hashids
            // @todo - explain in readme the hashids config file

            $link->short_code = $this->createAHashShortCode($link->id);

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
        $linkId = $this->decodeAHashShortCode($shortCode);
        $link = $this->linkRepository->find($linkId);
        return $link;
    }
}
