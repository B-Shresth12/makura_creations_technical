<?php

namespace App\Services;

define('BASE58_CHARS', '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz');


use App\Models\Url;
use App\Repositories\Url\UrlRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UrlService
{

    public function __construct(protected UrlRepositoryInterface $urlRepository)
    {
        // 
    }

    /**
     * creating shorten link
     * @param string
     * Here the link is checked if the link has exists or not in the system
     * if true then
     *      check if the url has been expired or not 
     *          if not then the @return Url 
     *          else
     *              update the existing url with new expiry date and shortcode
     * else
     *      create new url
     *      @return Url    
     */
    public function createShortenLink($requestedUrl)
    {
        $this->checkUrl($requestedUrl);
        $dbUrl = Url::where('url', $requestedUrl)->first();
        if (@$dbUrl) {
            if ($dbUrl->expired) {
                //$dbUrl = $this->urlRepository->updateUrl($data, $id);
            }
            return $dbUrl;
        }

        $data = [
            "short_code" => $this->generateShortCode(),
            "url" => $requestedUrl
        ];
        return $this->urlRepository->createShortenLink($data);
    }

    /**
     * this function check if the url provided
     * is valid or not
     * @return null
     * @param string
     */
    private function checkUrl($url)
    {
        // admin permission to run this
        // validate url
        // if (!filter_var($url, FILTER_VALIDATE_URL)) {
        //     return 'The provided URL is not valid.';
        // }

        // //check if the url is online
        // try {
        //     $response = Http::head($url);
        // } catch (\Exception $e) {
        //     return "Url is not online";
        // }

        return null;
    }

    /**
     * generate a unique short code for the url
     * @return string
     */
    private function generateShortCode()
    {
        $shortCode = strtoupper(Str::random(6, BASE58_CHARS));
        $shortExists = Url::where('short_code', $shortCode)->exists();

        if ($shortExists) {
            return $this->generateShortCode();
        }

        return $shortCode;
    }
}
