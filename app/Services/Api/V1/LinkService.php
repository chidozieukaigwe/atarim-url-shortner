<?php

namespace App\Services\Api\V1;

use App\Exceptions\FailedToShortUrlException;
use App\Models\Link;
use App\Repositories\LinkRepositoryInterface;
use App\Services\HashIdsService;
use Exception;
use Illuminate\Support\Facades\Log;

class LinkService
{
    protected LinkRepositoryInterface $linkRepository;
    protected HashIdsService $hashIdsService;

    public function __construct(LinkRepositoryInterface $linkRepository, HashIdsService $hashIdsService)
    {
        $this->linkRepository = $linkRepository;
        $this->hashIdsService = $hashIdsService;
    }

    public function generateShortUrl(string $originalUrl): Link
    {
        try {

            $existingUrl = $this->linkRepository->where('original_url', $originalUrl);


            if ($existingUrl) {
                return $existingUrl;
            }

            $link = $this->linkRepository->create([
                'original_url' => $originalUrl
            ]);

            // @todo link this is readme file - https://github.com/vinkla/laravel-hashids
            // @todo - explain in readme the hashids config file

            $link->short_code = $this->hashIdsService->createAHashShortCode($link->id);

            $link->save();

            Log::info('Link successfully shortened: ', ['link_details' => $link]);

            return $link;
        } catch (Exception $exception) {
            Log::error('Error shortening the url: ', ['exception' => $exception]);
            throw new FailedToShortUrlException();
        }
    }

    public function retrieveOriginalUrl(string $shortCode): Link
    {
        $linkId = $this->hashIdsService->decodeAHashShortCode($shortCode);
        $link = $this->linkRepository->find($linkId);
        return $link;
    }
}
