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
        $url = $this->urlService->createShortenLink(
            Helper::normalizeUr($data['url'])
        );

        if ($url) {
            return response()->json([
                'statusCode' => 200,
                'message' => "Url has been shortened",
                'shortCode' => $url->short_code,
            ]);
        }

        return response()->json([
            'statusCode' => 500,
            'message' => "Something went wrong. Please try again later"
        ]);
    }
}
