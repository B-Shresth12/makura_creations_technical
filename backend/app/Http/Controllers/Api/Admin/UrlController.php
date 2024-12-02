<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UrlResource;
use App\Services\UrlService;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    function __construct(protected UrlService $urlService) {}
    public function index()
    {
        return $this->urlService->getAllUrls();
    }

    public function show($shortCode)
    {
        return $this->urlService->showUrl($shortCode);
    }

    public function destroy($shortCode)
    {
        return $this->urlService->deleteUrl($shortCode);
    }

    public function lookUp(Request $request)
    {
        $request->validate([
            'shortCode' => "required|exists:urls,short_code",
        ]);

        return $this->urlService->showUrl($request->shortCode);
    }

    public function search(Request $request)
    {
        $request->validate([
            "url" => "required|url"
        ]);

        return $this->urlService->seachByUrl(Helper::normalizeUrl($request->url));
    }
}
