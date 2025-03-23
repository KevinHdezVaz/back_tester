<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'creator',
        'description',
        'category',
        'logo_path',
        'apk_path',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relación con el usuario (opcional, ya que tienes user_id como clave foránea)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}