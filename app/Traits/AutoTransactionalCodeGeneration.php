<?php

namespace App\Traits;

use App\Traits\HasCodeGeneration;

trait AutoTransactionalCodeGeneration {
    use HasCodeGeneration;

    /**
     * Configuration for auto resource code generation.
     * Override this method in model
     *
     * @return array
     */
    abstract public function getTransactionalCodeConfig(): array;

    protected static function bootAutoTransactionalCodeGeneration(): void {
        static::creating(function ($model) {
            $config = $model->getTransactionalCodeConfig();

            if (!empty($config['column']) && !empty($config['transaction_prefix']) && !empty($config['resource_prefix'])) {
                if (empty($model->{$config['column']})) {
                    $model->{$config['column']} = $model->generateTransactionalCode(
                        $config['transaction_prefix'],
                        $config['resource_prefix'],
                        $config['column']
                    );
                }
            }
        });
    }
}