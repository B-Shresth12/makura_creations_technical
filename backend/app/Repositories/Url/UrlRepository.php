<?php

namespace App\Repositories\Url;

use App\Models\Url;

class UrlRepository implements UrlRepositoryInterface
{
  /**
   * Get all rows
   */
  public function getAll($filter = [], $page = 10)
  {
    $urls = Url::filter($filter);
    if ($page == 0) {
      return $urls->get();
    }
    return $urls->paginate($page);
  }

  /**
   * create shorten link 
   * @return Url
   */
  public function createShortenLink(array $data)
  {
    return Url::create($data);
  }

  /**
   * get url from shortCode
   */
  public function getUrl($shortCode)
  {
    $url = Url::find($shortCode);
    return $url;
  }

  /**
   * update url 
   */
  public function updateUrl($data, $shortCode)
  {
    $url =  Url::where('short_code', $shortCode)->first();
    $url->update($data);
    return $url;
  }

  /**
   * delete url
   */
  public function deleteUrl(Url $url)
  {
    $url->delete();
  }

  public function searchByUrl($url)
  {
    return Url::where('url', $url)->first();
  }
}
