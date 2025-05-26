<?php

namespace App\Traits;

use App\Traits\HasCodeGeneration;

trait AutoCodeGeneration {
    use HasCodeGeneration;

    /**
     * Configuration for auto code generation
     * Override this method in model
     *
     * @return array
     */
    abstract public function getCodeConfig(): array;

    /**
     * Boot method will be called automatically by Laravel
     */
    protected static function bootAutoCodeGeneration(): void
    {
        static::creating(function ($model) {
            $config = $model->getCodeConfig();

            if (!empty($config['column']) && !empty($config['prefix'])) {
                if (empty($model->{$config['column']})) {
                    $model->{$config['column']} = $model->generateCode(
                        $config['prefix'],
                        $config['column']
                    );
                }
            }
        });
    }
}