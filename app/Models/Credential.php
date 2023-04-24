<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property string $credential_id
 * @property string $credential_public_key
 */
class Credential extends Model
{
    protected $guarded = ['id'];
}
