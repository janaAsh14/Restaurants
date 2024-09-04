<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'owner_id',
        'price',
        'image',
        'desc',
        'active',
        'section_id' ,
        'category_id'
    ];
    public function owner()
    {
        return $this->belongsTo(AdminOwner::class, 'owner_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
