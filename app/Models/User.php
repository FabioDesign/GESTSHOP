<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'gender',
        'number',
        'email',
        'password',
        'password_at',
        'avatar',
        'login_at',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'profile_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    // Génération de UUID unique
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uid)) {
                $model->uid = Str::uuid()->toString();
            }
        });
    }

    // Relation avec le profil
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    // Relation avec la ville
    public function town()
    {
        return $this->belongsTo(Town::class, 'town_id');
    }

    // Relation avec le Pays
    public function country()
    {
        return $this->belongsTo(Country::class, 'embassy_id');
    }

    // Relation avec le Nationality
    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }
}
