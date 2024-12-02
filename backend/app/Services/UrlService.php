<?php

namespace App\Services;

define('BASE58_CHARS', '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz');

use App\Http\Resources\UrlResource;
use App\Jobs\IncrementHitCount;
use App\Models\Url;
use App\Repositories\Url\UrlRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class UrlService
{

    public function __construct(protected UrlRepositoryInterface $urlRepository)
    {
        // 
    }

    /**
     * get all urls
     */
    public function getAllUrls()
    {
        $urls =  $this->urlRepository->getAll();
        return response()->json([
            'statusCode' => 200,
            'message' => "Url records has been fetched",
            'data' => [
                'urls' => UrlResource::collection($urls),
                'pagination' => [
                    "current_page" => $urls->currentPage(),
                    "first_page_url" =>  $urls->getOptions()['path'] . '?' . $urls->getOptions()['pageName'] . '=1',
                    "prev_page_url" =>  $urls->previousPageUrl(),
                    "next_page_url" =>  $urls->nextPageUrl(),
                    "last_page_url" =>  $urls->getOptions()['path'] . '?' . $urls->getOptions()['pageName'] . '=' . $urls->lastPage(),
                    "last_page" =>  $urls->lastPage(),
                    "per_page" =>  $urls->perPage(),
                    "total" =>  $urls->total(),
                    "total_page" => ceil($urls->total() / $urls->perPage()),
                    "path" =>  $urls->getOptions()['path'],
                ],
            ]
        ]);
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
    public function createShortenLink($requestedUrl, $expiryDate)
    {
        $this->checkUrl($requestedUrl);
        $dbUrl = Url::where('url', $requestedUrl)->first();
        if (@$dbUrl) {
            if ($dbUrl->expired) {
                $url = $this->urlRepository->updateUrl([
                    "short_code" => $this->generateShortCode(),
                    'expires_at' => $expiryDate,
                    "expired" => ($expiryDate > date('Y-m-d')) ? 0 : 1
                ], $dbUrl->short_code);
            } else {
                $url = $dbUrl;
            }
            goto returnResponse;
        }

        $data = [
            "short_code" => $this->generateShortCode(),
            "url" => $requestedUrl,
            'expires_at' => $expiryDate,
        ];
        $url =  $this->urlRepository->createShortenLink($data);

        returnResponse:
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

    public function redirectShortCode($shortCode, $ip)
    {
        $url = json_decode(Redis::get($shortCode));
        if (!@$url) {
            $url = $this->urlRepository->getUrl($shortCode);
            $notFound = true;
        }
        if (!@$url) {
            return response()->json([
                'statusCode' => 404,
                'message' => "Url does not exists"
            ]);
        }
        if ($url->expired) {
            return response()->json([
                'statusCode' => 410,
                'message' => "Url has been expired",
            ], 410);
        }

        if (@$notFound) {
            Redis::set($url->short_code, json_encode([
                "expired" => $url->expired,
                "url" => $url->url
            ]));
        }

        IncrementHitCount::dispatch($shortCode);

        return response()->json([
            'statusCode' => 302,
            'message' => 'Short Code found',
            'data' => [
                'url' => $url
            ],
        ], 302);
    }
    public function deleteUrl($shortCode)
    {
        $url = $this->urlRepository->getUrl($shortCode);
        if (!@$url) {
            return response()->json([
                'statusCode' => 404,
                'message' => "URL not found",
            ], 404);
        }
        $this->urlRepository->deleteUrl($url);

        return response()->json([
            'statusCode' => 410,
            'message' => "URL has been deleted",
        ], 410);
    }

    public function showUrl($shortCode)
    {
        $url = $this->urlRepository->getUrl($shortCode);
        if (!@$url) {
            return response()->json([
                'statusCode' => 404,
                'message' => "URL not found",
            ], 404);
        }

        return response()->json([
            'statusCode' => 200,
            'message' => "URL Data has been fetched",
            'data' => UrlResource::make($url)
        ], 200);
    }
    public function checkExpiredUrl()
    {
        $filter = [
            'expiryCheck' => true,
        ];
        $urls = $this->urlRepository->getAll(filter: $filter, page: 0);
        foreach ($urls as $url) {
            $this->urlRepository->updateUrl([
                "expired" => 1,
            ],$url->short_code);
        }
    }
}
