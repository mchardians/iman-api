<?php

namespace App\Traits;

use App\Libraries\CodeGeneration;

trait HasCodeGeneration {
    public function generateResourceCode(string $prefix, string $column = 'code'): string
    {
        $codeGenerator = new CodeGeneration(model: static::class, column: $column, prefix: $prefix);

        return $codeGenerator->getGeneratedResourceCode();
    }

    public function generateTransactionalCode(string $transactionPrefix, string $resourcePrefix, string $column = 'code'): string {
        $codeGenerator = new CodeGeneration(model: static::class, column: $column, prefix: $resourcePrefix);

        return $codeGenerator->getTransactionalCode($transactionPrefix);
    }
}