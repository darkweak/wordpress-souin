<?php

require_once(__DIR__ . '/SelectField.php');

class LogLevelSelectField extends SelectField
{
    protected $options = [
        'DEBUG',
        'INFO',
        'WARNING',
        'ERROR',
    ];
}
