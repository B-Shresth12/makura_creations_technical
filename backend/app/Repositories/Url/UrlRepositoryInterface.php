<?php

namespace App\Repositories\Url;

interface UrlRepositoryInterface
{
    public function createShortenLink(array $data);
}
