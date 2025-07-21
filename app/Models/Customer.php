<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname', 'lastname', 'email', 'mobile','gender','address','hobbies'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    protected $casts = [
        'hobbies' => 'array', // <--- This is essential for JSON handling
    ];
}
