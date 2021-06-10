<?php

require_once(__DIR__ . '/TextField.php');

class BasePathField extends TextField
{
    public function __construct($label = 'basepath', $parent = null, $initialValue)
    {
        parent::__construct($parent ? \sprintf('%s[%s]', $parent, 'basepath') : 'basepath', $label, $initialValue);
    }
}
