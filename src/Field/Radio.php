<?php

namespace RoboGen\Wireform\Field;

use RoboGen\Wireform\Field;;

class Radio extends Field {
    public $type = 'radio';
    public $choices = [];

    public function set_choices($choices) {
        $this->choices = $choices;
        return $this;
    }
}