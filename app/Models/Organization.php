<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Модель организации",
 *     type="object",
 *     required={"name", "building_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Уникальный идентификатор организации"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Название организации"
 *     ),
 *     @OA\Property(
 *         property="phone_numbers",
 *         type="array",
 *         @OA\Items(type="string"),
 *         description="Список номеров телефона организации"
 *     ),
 *     @OA\Property(
 *         property="building_id",
 *         type="integer",
 *         description="ID здания, в котором находится организация"
 *     )
 * )
 */

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone_numbers', 'building_id'];

    protected $casts = [
        'phone_numbers' => 'array',
    ];

    /**
     * Получение здания, к которому принадлежит организация.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Получение видов деятельности, к которым относится организация.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

}
