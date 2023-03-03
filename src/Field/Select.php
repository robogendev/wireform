<?php

namespace RoboGen\Wireform\Field;

use RoboGen\Wireform\Field;;

class Select extends Field {
    public $type = 'select';
    public $options = [];

    public function __construct(string $key, ?string $label = null) {
        parent::__construct($key, $label);
    }

    public function set_options(array $options): self {
        if(!isset($this->value)) {
            $this->set_value(array_key_first($options));
        }
        
        $this->options = $options;
        return $this;
    }

    public function get_options(): array {
        return $this->options;
    }
}
