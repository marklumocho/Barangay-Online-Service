<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'resident_id', 'first_name', 'last_name',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function applications() {
        return $this->hasMany(Application::class);
    }
}