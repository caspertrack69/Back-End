<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $return = parent::toArray($request);
        unset($return['updated_at']);
        return $return;
    }
}
