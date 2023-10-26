<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }




    protected $fillable = [
        'name',
        'lastName',
        'company_id',
        'email',
        'phone'
    ];
}
