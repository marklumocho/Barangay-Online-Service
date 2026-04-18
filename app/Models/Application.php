<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model {
    protected $fillable = [
        'user_id', 'resident_name', 'resident_id',
        'document_type', 'purpose', 'notes', 'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}