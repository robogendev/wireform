<?php

namespace RoboGen\Wireform;

class Field {
    public $type;
    public $key;
    public $label;
    public $description;
    public $visibility = true;
    public $conditionals;

    public function __construct(string $key, ?string $label = null) {
        $this->key = $key;
        $this->label = $label;
    }

    public function set_key(string $key) {
        $this->key = $key;
        return $this;
    }

    public function get_key() {
        return $this->key;
    }

    public function set_label(string $label) {
        $this->label = $label;
        return $this;
    }

    public function get_label() {
        return $this->label;
    }

    public function set_description(string $description) {
        $this->description = $description;
        return $this;
    }

    public function get_description() {
        return $this->description;
    }

    public function set_visibility(bool $visibility) {
        $this->visibility = $visibility;
        return $this;
    }

    public function get_visibility() {
        return $this->visibility;
    }

    public function add_conditional(Field $field, string $operator, $value) {
        $this->conditionals[] = [
            'field' => $field,
            'operator' => $operator,
            'value' => $value
        ];
        return $this;
    }

    public function add_conditionals(array $conditionals) {
        foreach ($conditionals as $conditional) {
            $this->conditionals[] = $conditional;
        }
        return $this;
    }

    public function get_conditionals() {
        return $this->conditionals;
    }

    public function render() {
        return view("wireform::components.{$this->type}");
    }
}
