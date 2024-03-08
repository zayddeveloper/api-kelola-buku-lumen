<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model{
    protected $table = "users";

    protected $fillable = ['nama','username','password'];

    protected $hidden = ['password'];
}