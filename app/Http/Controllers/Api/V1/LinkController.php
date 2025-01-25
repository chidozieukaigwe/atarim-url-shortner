<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreLinkRequest;
use App\Models\Link;
use App\Services\Api\V1\LinkService;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function store(StoreLinkRequest $request, LinkService $linkService)
    {
        $link = $linkService->generateShortUrl($request->original_url);
    }

    public function show(Link $link, LinkService $linkService)
    {
        $originalUrl = $linkService->retrieveOriginalUrl($link->short_code);
        dd($originalUrl);
    }
}
