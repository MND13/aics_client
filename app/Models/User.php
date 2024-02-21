<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'ext_name',
        'gender',
        'birth_date',
        'psgc_id',
        'password',
        'mobile_number',
        'street_number',
        'username',
        'email',
        'office_id',
        'meta_full_name',
        'full_name',
        'mobile_verified',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
        self::updating(function ($model) {
            $model->full_name = trim($model->first_name . " " . $model->middle_name . " " . $model->last_name . " " . $model->ext_name);
            $model->meta_full_name = trim(metaphone($model->first_name) . metaphone($model->middle_name) . metaphone($model->last_name));
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*public function aics_client()
    {
        return $this->belongsTo(AicsClient::class);
    }*/

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function age()
    {

        return Carbon::parse($this->attributes['birth_date'])->age;
    }

    public function psgc()
    {
        return $this->belongsTo(Psgc::class);
    }
    public function office()
    {
        return $this->belongsTo(Offices::class);
    }

    public function profile_docs()
    {
        return $this->hasMany(ProfileDocuments::class);
    }

    public static function s3Url($path)
    {
        $path =  str_replace("/storage/", "public/", $path);

        if (Storage::disk("s3")->exists($path)) {

            $mimtype  = Storage::disk("s3")->mimeType($path);
            $foo  = Storage::disk("s3")->get($path);
            $base64 = "data:" . $mimtype . ";base64," . base64_encode($foo);
            return $base64;
        } else if (Storage::exists($path)) {
            $mimtype  = Storage::mimeType($path);
            $foo  = Storage::get($path);
            $base64 = "data:" . $mimtype . ";base64," . base64_encode($foo);
            return $base64;
        } else return url("images/broken-link.png");
    }

    public function getInitialsAttribute()
    {
        $initials = '';
        if ($this->first_name) {
            $initials .= substr($this->first_name, 0, 1);
        }

        if ($this->middle_name) {
            $initials .= substr($this->middle_name, 0, 1);
        }

        if ($this->last_name) {
            $initials .= substr($this->last_name, 0, 1);
        }

        return $initials;
    }
}
