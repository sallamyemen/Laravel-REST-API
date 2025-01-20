<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="Модель здания",
 *     type="object",
 *     required={"address", "latitude", "longitude"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Уникальный идентификатор здания"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="Адрес здания"
 *     ),
 *     @OA\Property(
 *         property="latitude",
 *         type="number",
 *         format="float",
 *         description="Широта расположения здания"
 *     ),
 *     @OA\Property(
 *         property="longitude",
 *         type="number",
 *         format="float",
 *         description="Долгота расположения здания"
 *     )
 * )
 */

class Building extends Model
{
    use HasFactory;

    protected $fillable = ['address', 'latitude', 'longitude'];

    /**
     * Получение организаций, расположенных в здании.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}
