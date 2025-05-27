<?php

namespace App\Traits;

use App\Traits\HasCodeGeneration;

trait AutoResorceCodeGeneration {
    use HasCodeGeneration;

    /**
     * Configuration for auto resource code generation
     * Override this method in model
     *
     * @return array
     */
    abstract public function getResourceCodeConfig(): array;

    /**
     * Boot method will be called automatically by Laravel
     */
    protected static function bootAutoResourceCodeGeneration(): void
    {
        static::creating(function ($model) {
            $config = $model->getResourceCodeConfig();

            if (!empty($config['column']) && !empty($config['prefix'])) {
                if (empty($model->{$config['column']})) {
                    $model->{$config['column']} = $model->generateResourceCode(
                        $config['prefix'],
                        $config['column']
                    );
                }
            }
        });
    }
}