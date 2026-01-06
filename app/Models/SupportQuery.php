<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportQuery extends Model
{
    protected $fillable = [
        'name',
        'email',
        'type',
        'issue_type',
        'subject',
        'description',
    ];
}
