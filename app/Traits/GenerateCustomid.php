<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GenerateCustomId
{
    public static function bootGenerateCustomId()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = self::generateId($model);
            }
        });
    }

    protected static function generateId($model)
    {
        $prefix = property_exists($model, 'idPredix') ? $model->idPredix : 'ID';

        $datePart = now()->format('Ymd');
        $randomPart = Str::padLeft(mt_rand(1, 9999), 4, '0');

        return $prefix . $datePart . $randomPart;
    }
}
