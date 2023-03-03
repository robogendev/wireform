<?php

namespace RoboGen\Wireform\Field;

use RoboGen\Wireform\Field;;

class Radio extends Field {
    public $type = 'radio';
    public $choices = [];

    public function set_choices(array $choices): self {
        $this->choices = $choices;
        return $this;
    }

    public function get_choices(): array {
        return $this->choices;
    }
}