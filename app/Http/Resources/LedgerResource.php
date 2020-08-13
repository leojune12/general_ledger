<?php

namespace App\Http\Resources;

use App\Models\Codes\AccountCode;
use App\Models\Codes\ProjectCode;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LedgerResource extends JsonResource
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
            'id' => $this->id,
            'date_encoded' => $this->date_encoded,
            'account_code' => AccountCode::find($this->account_code_id),
            'project_code' => ProjectCode::find($this->project_code_id),
            'description' => $this->description
        ];
    }
}
