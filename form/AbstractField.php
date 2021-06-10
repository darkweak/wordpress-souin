<?php


class AbstractField
{
    protected $name;
    protected $label;
    protected $value;

    /**
     * AbstractField constructor.
     * @param $name string
     * @param $label string
     * @param $initialValue mixed
     */
    public function __construct($name, $label, $initialValue = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $initialValue;
    }

    public function getLabel()
    {
        return <<<HTML
<label for="{$this->name}">{$this->label}</label>
HTML;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setInitialValue($value) {
        $this->value = $value;
    }

    public function getField()
    {
        return '';
    }

    public function field_cb()
    {
        echo $this->getField();
    }

    public function register($plugin, $section, $cb = null)
    {
        add_settings_field(
            $this->name,
            $this->label,
            $cb,
            $plugin,
            $section
        );
    }

    public function renderField($section, $plugin)
    {
        $this->register($plugin, $section, [$this, 'field_cb']);
    }
}
