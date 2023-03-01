<?php

namespace RoboGen\Wireform\Field;

use RoboGen\Wireform\Field;;

class View extends Field {
    public $type = 'view';
    public $view;

    public function set_view($view) {
        $this->view = $view;
        return $this;
    }
}
