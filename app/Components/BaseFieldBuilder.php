<?php

namespace Sibas\Components;

abstract class BaseFieldBuilder
{

    abstract protected function getType();


    protected function getNameAttribute($name, array $options)
    {
        if ( ! isset( $options['name'] )) {
            return $name;
        }
    }


    protected function getIdAttribute($name, array $options)
    {
        if (array_key_exists('id', $options)) {
            return $options['id'];
        }

        if (in_array($name, $options)) {
            return $name;
        }
    }


    protected function attributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if ( ! is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }


    protected function attributeElement($key, $value)
    {
        if (is_numeric($key)) {
            $key = $value;
        }

        if ( ! is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }


    protected function getDataAttribute(array $value)
    {
        $data = [];
        $keys = array_keys($value);

        foreach ($keys as $key) {
            if (strpos($key, 'data_') !== false) {
                $key_data        = str_replace('_', '-', $key);
                $data[$key_data] = $value[$key];
            }
        }

        return count($data) > 0 ? $data : null;
    }

}