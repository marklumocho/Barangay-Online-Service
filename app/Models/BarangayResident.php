<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayResident extends Model {
    protected $table = 'barangay_residents';

    protected $fillable = [
        'first_name', 'last_name', 'middle_initial',
        'resident_id', 'address',
    ];
}