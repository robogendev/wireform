<?php

namespace RoboGen\Wireform\Field;

use RoboGen\Wireform\Field;;

class Checkbox extends Field {
    public $type = 'checkbox';
    public $choices = [];

    public function __construct(string $key, ?string $label = null) {
        $this->key = "{$key}[]";
        $this->label = $label;
    }

    public function set_choices(array $choices): self {
        $this->choices = $choices;
        return $this;
    }

    public function get_choices(): array {
        return $this->choices;
    }
}