<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int id
 * @property string webauthn_id
 * @property string email
 * @property string name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Credential[] $credentials
 * @method static firstOrNew(array $check, array $attributes)
 * @method static firstWhere(string $string, mixed $input)
 * @method static create(mixed $user)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    public function credentials()
    {
        return $this->hasMany(Credential::class);
    }
}
