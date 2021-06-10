<?php

require_once(__DIR__ . '/AbstractContainerFields.php');
require_once(__DIR__ . '/Port.php');
require_once(__DIR__ . '/Regex.php');
require_once(__DIR__ . '/TextField.php');

class DefaultCache extends AbstractContainerFields
{
    public function __construct($initialValue)
    {
        $parent = 'configuration[default_cache]';
        parent::__construct([
            new Port(\sprintf('%s[port]', $parent), $initialValue ? $initialValue->port : null),
            new Regex(\sprintf('%s[regex]', $parent), $initialValue ? $initialValue->regex : null),
            new TextField(\sprintf('%s[ttl]', $parent), 'Cache duration (e.g. 10m for 10 minutes)', $initialValue ? $initialValue->ttl : null),
        ], 'souin_default_cache_configuration', 'Souin default cache configuration');
    }
}
