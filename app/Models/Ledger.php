<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $fillable = [
        'date_encoded', 'account_code_id', 'project_code_id', 'amount','description'
    ];

    public function medias() {
        return $this->hasMany(Media::class);
    }
}
