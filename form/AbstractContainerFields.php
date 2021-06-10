<?php

require_once(__DIR__ . '/AbstractField.php');

class AbstractContainerFields
{
    /** @var self[]|AbstractField[] */
    protected $fields;

    /** @var string */
    protected $section;

    /** @var string */
    protected $title;

    /**
     * AbstractContainerFields constructor.
     * @param $fields self[]|AbstractField[]
     * @param $title string
     */
    public function __construct($fields, $section, $title)
    {
        $this->fields = $fields;
        $this->section = $section;
        $this->title = $title;
    }

    public function getName()
    {
        return $this->section;
    }

    public function getLabel()
    {
        return '';
    }

    public function listFields()
    {
        return $this->fields;
    }

    public function register($_ = '', $_1 = '', $_2 = null)
    {
        add_settings_section(
            $this->section,
            $this->title,
            null,
            'souinPlugin'
        );
    }

    public function renderField($_ = null, $plugin = 'souinPlugin')
    {
        foreach ($this->fields as $field) {
            $field->register($plugin, $this->section);
            $field->renderField(
                $this->section,
                $plugin
            );
        }
    }
}
