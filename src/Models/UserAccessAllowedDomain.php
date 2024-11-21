<?php

namespace Sharqlabs\LaravelAccessGuard\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccessAllowedDomain extends Model
{
    protected $fillable = ['domain'];
}
