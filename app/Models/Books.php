<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Books extends Model{
    protected $table = "books";

    protected $fillable = [
        'user_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}