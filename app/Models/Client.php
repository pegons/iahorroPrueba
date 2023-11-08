<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'lead_id'];
    protected $casts = [
        'id' => 'string',
        'lead_id' => 'string',
    ];

    public $incrementing = false;

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
