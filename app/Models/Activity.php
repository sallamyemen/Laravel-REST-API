<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Модель деятельности",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Уникальный идентификатор деятельности"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Название деятельности"
 *     ),
 *     @OA\Property(
 *         property="parent_id",
 *         type="integer",
 *         description="Идентификатор родительской деятельности"
 *     ),
 *     @OA\Property(
 *         property="depth",
 *         type="integer",
 *         description="Глубина вложенности"
 *     )
 * )
 */

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    /**
     * Получение дочерних активностей.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    /**
     * Получение родительской активности.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    /**
     * Получение организаций, связанных с активностью.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }

    /**
     * Сохранение активности и вычисление глубины на основе родительской активности.
     *
     * @return void
     */

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
