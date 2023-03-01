<?php

namespace RoboGen\Wireform\Field;

use RoboGen\Wireform\Field;

class Steps extends Field {
    public $type = 'steps';
    public $key;
    public $steps;
    public $activeStep = 0;
    public $confirmation = false;
    public $confirmationTitle = 'Confirm';
    public $confirmationDescription;

    public function add_step($step) {
        if($step->type === 'group') {
            foreach($step->fields as $field) {
                $field->set_key($this->key . '.' . $field->key);
            }
        }

        $step->set_key("{$this->key}.{$step->key}");
        $this->steps[] = $step;
        return $this;
    }

    public function add_steps($steps) {
        foreach ($steps as $step) {
            $this->steps[] = $step;
        }
        return $this;
    }

    public function set_confirmation($confirmation) {
        $this->confirmation = $confirmation;
        return $this;
    }

    public function set_confirmation_title($confirmationTitle) {
        $this->confirmationTitle = $confirmationTitle;
        return $this;
    }

    public function set_confirmation_description($confirmationDescription) {
        $this->confirmationDescription = $confirmationDescription;
        return $this;
    }
}
