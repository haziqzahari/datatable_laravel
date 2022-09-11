<?php

namespace App\Models;

use App\DataTable\DataTableTraits;
use App\DataTable\DataTableInterface;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'status',
    ];

    protected $guarded = [];

}
