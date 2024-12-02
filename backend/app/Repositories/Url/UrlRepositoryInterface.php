<?php

namespace App\Repositories\Url;

use App\Models\Url;

interface UrlRepositoryInterface
{
    public function getAll($filter = [], $page = 10);
    public function createShortenLink(array $data);
    public function getUrl(string $shortCode);

    public function deleteUrl(Url $url);
    public function updateUrl($data, $shortCode);
}
