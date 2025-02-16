<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacts extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "contacts";
    protected $fillable = [
        'name',
        'contact_no',
        'created_at',
        'updated_at'
    ];
}
