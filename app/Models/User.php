<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable

{

    use Notifiable, HasPushSubscriptions, HasFactory;

    /**

     * The attributes that are mass assignable.

     *

     * @var list<string>

     */

    protected $fillable = [

        "user_id",

        'name',

        'mobile',

        'email',

        'personal_email',

        'password',
         'password_hint',

        'profile_image',

        'role',

        'designation',

        'intern_period',

        'address',

        'dob',

        'doj',

        'is_active',

        'otp',

        'blocked_at',

        'is_break',

        'otp_verified',

        'otp_expires_at',

        'type',

    ];

    /**

     * The attributes that should be hidden for serialization.

     *

     * @var list<string>

     */

    protected $hidden = [

        'password',

        'remember_token',

    ];

    /**

     * Get the attributes that should be cast.

     *

     * @return array<string, string>

     */

    protected function casts(): array

    {

        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

        ];

    }

      public function pushSubscriptions()

    {

        return $this->hasMany(PushSubscription::class);

    }

   public function tasks()

    {

        return $this->hasMany(Task::class, 'assign_to', 'id');

    }
  

}

