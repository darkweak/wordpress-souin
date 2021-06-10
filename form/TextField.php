<?php

require_once(__DIR__ . '/AbstractField.php');

class TextField extends AbstractField
{
    protected $value = '';

    public function getField()
    {
        return <<<HTML
<div>
  <input type="text" id="{$this->name}" name="{$this->name}" value="{$this->value}" />
</div>
HTML;
    }
}
