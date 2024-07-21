<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'cover_photo_path',
        'bio',
        'birthdate',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'website',
        'social_profiles',
        'preferred_language',
        'email_notifications',
        'sms_notifications',
        'whatsapp_notifications',
        'occupation',
        'company',
        'education',
        'identity_document_type',
        'identity_document_number',
        'profile_visibility',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'social_profiles' => 'array',
    ];

    // Relación uno a uno inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
