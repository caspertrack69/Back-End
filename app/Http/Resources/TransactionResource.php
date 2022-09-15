<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            //'user_id' => $this->user_id,
            //'ref_trans_id' => $this->ref_trans_id,
            'transaction_id' => $this->transaction_id,
            'transaction_type' => $this->transaction_type,
            'debit' => (float)$this->debit,
            'credit' => (float)$this->credit,
            //'balance' => $this->balance,
            //'from_user_id' => $this->from_user_id,
            'user_info' => User::select(['phone', DB::raw("CONCAT(f_name, ' ' ,l_name) AS name")])->find($this->to_user_id),

            'sender' => User::select(['phone', DB::raw("CONCAT(f_name, ' ' ,l_name) AS name")])->find($this->from_user_id),
            'receiver' => User::select(['phone', DB::raw("CONCAT(f_name, ' ' ,l_name) AS name")])->find($this->to_user_id),

            'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            'amount' => (float)($this->debit + $this->credit),
        ];
    }
}
