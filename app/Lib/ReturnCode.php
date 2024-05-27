<?php

namespace App\Lib;

enum ReturnCode
{
    /**
     * Global Success
     * @var $success
     * @var $successInsert
     */
    case SUCCESS;

    /**
     * Global Failed
     * @var $failed
     * @var $failedInsert
     */
    case FAILED;
    case ERROR;
    case DATA_NOT_FOUND;

    /**
     * Validation
     * @var int $validationError
     */
    case VALIDATION_ERROR;

    /**
     * Duplicate
     * @var int $insertDuplicateName
     */
    case INSERT_DUPLICATE_VALUE;

    function code()
    {
        return match ($this) {
            ReturnCode::SUCCESS => 0,
            ReturnCode::FAILED => 10,
            ReturnCode::DATA_NOT_FOUND => 13,
            ReturnCode::VALIDATION_ERROR => 16,
            ReturnCode::INSERT_DUPLICATE_VALUE => 19,
            ReturnCode::ERROR => 99,
        };
    }

    function msg()
    {
        return match ($this) {
            ReturnCode::SUCCESS => "Success",
            ReturnCode::FAILED => "Failed",
            ReturnCode::DATA_NOT_FOUND => "Data Not Found",
            ReturnCode::VALIDATION_ERROR => "Validation Error",
            ReturnCode::INSERT_DUPLICATE_VALUE => "Failed insert data into database on duplicate value, already exist",
            ReturnCode::ERROR => "Operation Error",
        };
    }
}
