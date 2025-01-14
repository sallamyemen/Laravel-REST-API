<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($activity) {
            // Вычисляем глубину на основе родительской активности
            if ($activity->parent_id) {
                $parent = Activity::find($activity->parent_id);
                $activity->depth = $parent ? $parent->depth + 1 : 0;
            } else {
                $activity->depth = 0; // Для корневых активностей depth будет равен 0
            }
        });
    }
}
