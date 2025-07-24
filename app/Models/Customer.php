<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // for CustomerFactory use 

class Customer extends Model
{
    use HasFactory; // for CustomerFactory use
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
    protected $fillable = ['firstname', 'lastname', 'email', 'mobile', 'gender', 'address', 'hobbies'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    protected $casts = [
        'hobbies' => 'array', // <--- This is essential for JSON handling
    ];

    /**
     * Get the full name of the customer by concatenating first and last name.
     *
     * This is an accessor for the virtual `full_name` attribute.
     * 
     * @return string The full name (e.g., "John Doe")
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Get the number of hobbies associated with the customer.
     *
     * If no hobbies are set, it returns 0. Assumes hobbies is an array or null.
     * 
     * @return int The count of hobbies
     */
    public function getHobbyCount(): int
    {
        return count($this->hobbies ?? []);
    }
}
