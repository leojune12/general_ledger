<?php

namespace App\Http\Resources;

use App\Models\Codes\AccountCode;
use App\Models\Codes\ProjectCode;
use Illuminate\Http\Resources\Json\JsonResource;

class LedgerExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'date_encoded' => $this->date_encoded,
            'account_code' => AccountCode::find($this->account_code_id)->description,
            'project_code' => ProjectCode::find($this->project_code_id)->description,
            'amount' => $this->amount,
            'description' => $this->description
        ];
    }
}
