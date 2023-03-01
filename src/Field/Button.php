<?php

namespace RoboGen\Wireform\Field;

use RoboGen\Wireform\Field;

class Button extends Field {
    public $type = 'button';
    public $style = 'primary';

    public function set_style($style) {
        $this->style = $style;
        return $this;
    }
}
