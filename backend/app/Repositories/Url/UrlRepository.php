<?php

namespace App\Repositories\Url;

use App\Models\Url;

class UrlRepository implements UrlRepositoryInterface
{
  /**
   * create shorten link 
   * @return Url
   */
  public function createShortenLink(array $data)
  {
    return Url::create($data);
  }
}
