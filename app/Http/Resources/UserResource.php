<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use JsonSerializable;

/**
 * @property int $id
 * @property string $avatar
 * @property string $name
 * @property string $biography
 * @property string $daily_status
 * @property string $banner
 * @property Carbon $created_at
 */
class UserResource extends JsonResource
{

    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'name' => $this->name,
            'biography' => $this->biography,
            'daily_status' => $this->daily_status,
            'banner' => [
                'type' => ! URL::isValidUrl($this->banner) ? "URL" : "COLOR",
                'content' => $this->banner,
            ],
            'created_at' => $this->created_at->toString(),
        ];
    }
}
