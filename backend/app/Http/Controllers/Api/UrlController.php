<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLinkRequest;
use App\Services\UrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Helper\Helper;

class UrlController extends Controller
{
    function __construct(protected UrlService $urlService) {}

    public function createShortenLink(CreateLinkRequest $request)
    {
        $data = $request->validated();
        return $this->urlService->createShortenLink(
            Helper::normalizeUr($data['url']),
            $data['expires_at']
        );

        
    }

    public function redirect($shortCode, Request $request)
    {
        return $this->urlService->redirectShortCode($shortCode, $request->ip);
    }
}
