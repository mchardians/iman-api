<?php
namespace App\Libraries;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Exception;

/**
 * CodeGeneration Library
 *
 * Generates unique codes for resources and transactions.
 *
 * Resource format: PREFIX/YYMM/0001
 *
 * Transactional format: TRANSACTIONPREFIX/RESOURCEPREFIX/YYYYMMDD/0001
 */
class CodeGeneration {
    private string $model;
    private string $column;
    private string $prefix;
    private ?string $code = null;
    private const SEQUENCE_LENGTH = 4;
    private const PREFIX_LENGTH = 5;
    private const TRANSACTION_PREFIX_LENGTH = 3;

    /**
     * Constructor
     *
     * @param string $model Model class name
     * @param string $column Database column name for storing codes
     * @param string $prefix Code prefix (maximum @property PREFIX_LENGTH characters)
     * @throws InvalidArgumentException
     */
    public function __construct(string $model, string $column, string $prefix) {
        $this->validateInputs($model, $column, $prefix);

        $this->model = $model;
        $this->column = $column;
        $this->prefix = strtoupper(trim($prefix));
        $this->generateResourceCode();
    }

    /**
     * Get generated resource code
     *
     * @return string Generated resource code
     */
    public function getGeneratedResourceCode(): string {
        return $this->code ?? '';
    }

    /**
     * Generate transactional code
     *
     * @param string $transactionPrefix Transaction prefix (maximum @property TRANSACTION_PREFIX_LENGTH characters)
     * @return string Generated transactional code
     * @throws InvalidArgumentException
     */
    public function getTransactionalCode(string $transactionPrefix): string {
        $this->validateTransactionPrefix($transactionPrefix);

        try {
            return DB::transaction(function () use ($transactionPrefix) {
                $transactionPrefix = strtoupper(trim($transactionPrefix));
                $lastCode = $this->getLastTransactionalCodeRecord($transactionPrefix);
                $currentDate = date("Ymd");

                if ($lastCode) {
                    $codeParts = explode("/", $lastCode->{$this->column});

                    if (count($codeParts) >= 4) {
                        $lastDate = $codeParts[2];
                        $lastNumber = (int)$codeParts[3];

                        if ($lastDate === $currentDate) {
                            $newNumber = $lastNumber + 1;
                            $postfix = str_pad($newNumber, self::SEQUENCE_LENGTH, '0', STR_PAD_LEFT);
                            return "{$transactionPrefix}/{$this->prefix}/{$currentDate}/{$postfix}";
                        }
                    }
                }

                return "{$transactionPrefix}/{$this->prefix}/{$currentDate}/" . str_repeat('0', self::SEQUENCE_LENGTH - 1) . '1';
            });
        } catch (Exception $e) {
            Log::error('Failed to generate transactional code', [
                'model' => $this->model,
                'column' => $this->column,
                'prefix' => $this->prefix,
                'transactionPrefix' => $transactionPrefix,
                'error' => $e->getMessage()
            ]);
            throw new Exception("Failed to generate transactional code: " . $e->getMessage());
        }
    }

    /**
     * Validate constructor inputs
     *
     * @param string $model
     * @param string $column
     * @param string $prefix
     * @throws InvalidArgumentException
     */
    private function validateInputs(string $model, string $column, string $prefix): void {
        if (empty($model) || !class_exists($model)) {
            throw new InvalidArgumentException("Invalid model class: {$model}");
        }

        if (empty($column)) {
            throw new InvalidArgumentException("Column name cannot be empty");
        }

        $this->validatePrefix($prefix);
    }

    /**
     * Validate resource prefix
     *
     * @param string $prefix
     * @param string $type
     * @throws InvalidArgumentException
     */
    private function validatePrefix(string $prefix): void {
        $trimmedPrefix = trim($prefix);

        if (empty($trimmedPrefix)) {
            throw new InvalidArgumentException("Prefix cannot be empty");
        }

        if (strlen($trimmedPrefix) > self::PREFIX_LENGTH) {
            throw new InvalidArgumentException("Prefix must not exceed " . self::PREFIX_LENGTH . " characters");
        }

        if (!ctype_alnum($trimmedPrefix)) {
            throw new InvalidArgumentException("Prefix must contain only alphanumeric characters");
        }
    }

    /**
     * Validate transaction prefix for transactional code
     *
     * @param string $transactionPrefix
     * @throws InvalidArgumentException
     */
    private function validateTransactionPrefix(string $transactionPrefix): void {
        $trimmedPrefix = trim($transactionPrefix);

        if (empty($trimmedPrefix)) {
            throw new InvalidArgumentException("Transaction prefix cannot be empty");
        }

        if (strlen($trimmedPrefix) > self::TRANSACTION_PREFIX_LENGTH) {
            throw new InvalidArgumentException("Transaction prefix must not exceed " . self::TRANSACTION_PREFIX_LENGTH . " characters");
        }

        if (!ctype_alnum($trimmedPrefix)) {
            throw new InvalidArgumentException("Transaction prefix must contain only alphanumeric characters");
        }
    }

    /**
     * Generate resource code with database transaction
     */
    private function generateResourceCode(): void {
        try {
            $this->code = DB::transaction(function () {
                $lastCode = $this->getLastResourceCodeRecord();
                $currentYear = date("y");
                $currentMonth = date("m");
                $currentYearMonth = $currentYear . $currentMonth;

                if ($lastCode) {
                    $codeParts = explode("/", $lastCode->{$this->column});

                    if (count($codeParts) >= 3) {
                        $lastYearMonth = $codeParts[1];
                        $lastNumber = (int)$codeParts[2];

                        if ($lastYearMonth === $currentYearMonth) {
                            $newNumber = $lastNumber + 1;
                            $postfix = str_pad($newNumber, self::SEQUENCE_LENGTH, '0', STR_PAD_LEFT);
                            return "{$this->prefix}/{$currentYearMonth}/{$postfix}";
                        }
                    }
                }

                return "{$this->prefix}/{$currentYearMonth}/" . str_repeat('0', self::SEQUENCE_LENGTH - 1) . '1';
            });
        } catch (Exception $e) {
            Log::error('Failed to generate resource code', [
                'model' => $this->model,
                'column' => $this->column,
                'prefix' => $this->prefix,
                'error' => $e->getMessage()
            ]);
            throw new Exception("Failed to generate resource code: " . $e->getMessage());
        }
    }

    /**
     * Get last resource code record with proper parameter binding
     *
     * @return object|null
     */
    private function getLastResourceCodeRecord(): ?object {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return $this->model::select($this->column)
            ->whereMonth("created_at", $currentMonth)
            ->whereYear("created_at", $currentYear)
            ->where($this->column, 'LIKE', $this->prefix . '/%')
            ->orderByDesc($this->column)
            ->lockForUpdate()
            ->first();
    }

    /**
     * Get last transactional code record with proper parameter binding
     *
     * @param string $transactionPrefix
     * @return object|null
     */
    private function getLastTransactionalCodeRecord(string $transactionPrefix): ?object {
        $currentDate = date("Ymd");
        $prefixPattern = $transactionPrefix . '/' . $this->prefix . '/' . $currentDate . '/%';

        return $this->model::select($this->column)
            ->whereDate("created_at", Carbon::today())
            ->where($this->column, 'LIKE', $prefixPattern)
            ->orderByDesc($this->column)
            ->lockForUpdate()
            ->first();
    }
}