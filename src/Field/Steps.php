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

    public function add_step($step): self {
        if($step->type === 'group') {
            foreach($step->fields as $field) {
                $field->set_key($this->key . '.' . $field->key);
            }
        }

        $step->set_key("{$this->key}.{$step->key}");
        $this->steps[] = $step;
        return $this;
    }

    public function add_steps($steps): self {
        foreach ($steps as $step) {
            $this->steps[] = $step;
        }
        return $this;
    }

    public function get_steps(): array {
        return $this->steps;
    }

    public function set_confirmation($confirmation) {
        $this->confirmation = $confirmation;
        return $this;
    }

    public function get_confirmation(): bool {
        return $this->confirmation;
    }

    public function set_confirmation_title($confirmationTitle): self {
        $this->confirmationTitle = $confirmationTitle;
        return $this;
    }

    public function get_confirmation_title(): ?string {
        return $this->confirmationTitle;
    }

    public function set_confirmation_description($confirmationDescription): self {
        $this->confirmationDescription = $confirmationDescription;
        return $this;
    }

    public function get_confirmation_description(): ?string {
        return $this->confirmationDescription;
    }
}
