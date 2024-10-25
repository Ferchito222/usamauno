<?php

namespace Sibas\Components;

class SelectFieldBuilder extends BaseFieldBuilder
{

    /**
     * @var array
     */
    private $html;


    protected function getType()
    {
        return 'select';
    }


    public function input($name, array $list = [], array $options = [], $selected = null)
    {
        $this->html = [];

        $options['type'] = $this->getType();
        $options['id']   = $this->getIdAttribute($name, $options);
        $options['name'] = $this->getNameAttribute($name, $options);

        foreach ($list as $key => $value) {
            $this->html[] = $this->option((array) $value, $selected);
        }

        $options    = $this->attributes($options);
        $this->html = implode('', $this->html);

        return "<select {$options}>{$this->html}</select>";
    }


    private function option($value, $selected)
    {
        $text     = $value['name'];
        $selected = $this->getSelectedValue($value['id'], $selected);
        $data     = $this->getDataAttribute($value);

        $options = [
            'value'    => $value['id'],
            'selected' => $selected
        ];

        if (is_array($data)) {
            $options = array_merge($options, $data);
        }

        return "<option {$this->attributes($options)}>" . e($text) . "</option>";
    }


    private function getSelectedValue($value, $selected)
    {
        if (is_array($selected)) {
            return in_array($value, $selected) ? 'selected' : null;
        }

        return ( (string) $value == (string) $selected ) ? 'selected' : null;
    }

}