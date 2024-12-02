<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
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

    public function test(){
        return $this->urlService->checkExpiredUrl();
    }
}
