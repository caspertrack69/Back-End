<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RequestMoneyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender' => User::select(['phone', DB::raw("CONCAT(f_name, ' ' ,l_name) AS name")])->find($this->from_user_id),
            'receiver' => User::select(['phone', DB::raw("CONCAT(f_name, ' ' ,l_name) AS name")])->find($this->to_user_id),
            'type' => $this->type,
            'amount' => (float)$this->amount,
            'note' => $this->note,
            'created_at' => $this->created_at,
        ];
    }
}
