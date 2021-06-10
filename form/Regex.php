<?php

require_once(__DIR__ . '/AbstractField.php');
require_once(__DIR__ . '/TextField.php');

class Regex extends AbstractContainerFields
{
    public function __construct($parent = null, $initialValue)
    {
        parent::__construct([
            new TextField($parent ? \sprintf('%s[exclude]', $parent) : 'exclude', 'Exclude this regex from being cached', $initialValue ? $initialValue->exclude : null),
        ], 'souin_default_cache_regex_configuration', 'Souin Default Cache Regex configuration');
    }
}
