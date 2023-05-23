<?php

namespace App;

use Webpatser\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if(count($model->getCompositeKeys()) == 0 && $model->isAutoPrimary == true && (!isset($model->{$model->getKeyName()}) || !$model->{$model->getKeyName()}) )
            {
                $model->{$model->getKeyName()} = (string) Uuid::generate(4);
            }
        });
    }
}