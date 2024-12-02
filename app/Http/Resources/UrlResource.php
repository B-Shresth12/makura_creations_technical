<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  parent::toArray($request);
        unset($data['updated_at']);
        $data['expires_at'] = date('Y-m-d', strtotime($data['expires_at']));
        $data['created_at'] = date('Y-m-d', strtotime($data['created_at']));
        return $data;
    }
}
