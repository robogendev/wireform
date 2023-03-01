<?php

namespace RoboGen\Wireform\Field;

use RoboGen\Wireform\Field;

class Group extends Field {
    public $type = 'group';
    public $key;
    public $fields;

    public function add_field($field) {
        if($field->type === 'group') {
            foreach($field->fields as $sub_field) {
                $sub_field->set_key("{$this->key}.{$sub_field->key}");
            }
        }

        $field->set_key("{$this->key}.{$field->key}");
        $this->fields[] = $field;
        return $this;
    }

    public function add_fields($fields) {
        foreach ($fields as $field) {
            $this->fields[] = $field;
        }
        return $this;
    }

    public function add_step($step) {
        $this->fields[] = $step;
        return $this;
    }

    public function add_steps($steps) {
        foreach ($steps as $step) {
            $this->fields[] = $step;
        }
        return $this;
    }

    public function render() {
        return view('wireform::components.group');
    }
}
