<?php

namespace RoboGen\Wireform;

use Livewire\Component;
use Illuminate\Support\Arr;

class FormComponent extends Component {
    public $fields;
    public $data;

    public $stepConfirmationModal = false;
    public $stepConfirmationModalData = [];

    public function mount() {
        $this->fields = json_decode(json_encode($this->fields()), true);
        $this->fields = $this->handleConditionals();
    }

    public function updated() {        
        $this->fields = $this->handleConditionals();
    }

    public function submit() {
        $this->emit('formSubmitted', $this->data);
    }

    public function handleConditionals($fields = null) {
        $fields = $fields ?? $this->fields;

        foreach ($fields as &$field) {            
            $field = $this->handleFieldConditionals($field);
    
            if (!empty($field['fields'])) {
                $field['fields'] = $this->handleConditionals($field['fields']);
            }
    
            if (!empty($field['steps'])) {
                $field['steps'] = $this->handleConditionals($field['steps']);
            }
        }

        unset($field);
    
        return $fields;
    }

    public function handleFieldConditionals($field) {
        if (empty($field['conditionals'])) {
            return $field;
        }
    
        $visibility = $field['visibility'];
        $data = Arr::dot($this->data ?? []);
    
        foreach ($field['conditionals'] as $conditional) {
            $key = $conditional['field']['key'];
            $value = $conditional['value'];
            $operator = $conditional['operator'];
    
            if (array_key_exists($key, $data)) {
                $match = ($data[$key] === $value);
                if (($match && $operator === '==') || (!$match && $operator === '!=')) {
                    $visibility = true;
                } else {
                    $visibility = false;
                }
            } else {
                $visibility = ($operator === '!=');
            }
        }
    
        if (!$visibility) {
            $field['value'] = null;
        }

        $field['visibility'] = $visibility;

        return $field;
    }
    

    public function nextStep($key, $skip_confirmation = false) {
        $confirm = $this->findFieldProperty($key, 'confirmation');

        if($confirm && !$skip_confirmation) {
            $this->stepConfirmationModal = true;
            $this->stepConfirmationModalData = $this->buildStepConfirmationModalData($key);
            return;
        }

        $this->updateField($key, 'activeStep', '+1');

        $this->stepConfirmationModalData = [];
        $this->stepConfirmationModal = false;
    }

    public function previousStep($key) {
        $this->updateField($key, 'activeStep', '-1');
    }

    private function buildStepConfirmationModalData($key) {
        $step = $this->findFieldProperty($key, 'steps')[$this->findFieldProperty($key, 'activeStep')];
        $data = [];

        if(empty($this->data)) {
            return $data;
        }

        foreach(Arr::dot($this->data) as $key => $value) {
            $label = $this->findFieldProperty($key, 'label', $step['fields']);

            if(!$label) {
                continue;
            }

            if($this->findFieldProperty($key, 'type', $step['fields']) === 'radio') {
                $choices = $this->findFieldProperty($key, 'choices', $step['fields']);
                $value = $choices[$value];
            }

            $data[] = [
                'label' => $label,
                'value' => $value,
            ];
        }

        return $data;
    }

    private function updateField($key, $property, $value) {
        $this->fields = $this->recursiveUpdateField($key, $property, $value, $this->fields);
    }

    private function recursiveUpdateField($key, $property, $value, &$fields) {
        foreach ($fields as &$field) {
            if ($field['key'] === $key) {
                if($value === '+1') {
                    $field[$property] += 1;
                } else if($value === '-1') {
                    $field[$property] -= 1;
                } else {
                    $field[$property] = $value;
                }
            }
    
            if (!empty($field['fields'])) {
                $field['fields'] = $this->recursiveUpdateField($key, $property, $value, $field['fields']);
            }
    
            if (!empty($field['steps'])) {
                $field['steps'] = $this->recursiveUpdateField($key, $property, $value, $field['steps']);
            }
        }
    
        return $fields;
    }

    protected function findField($key, $fields = null) {
        $fields = $fields ?? $this->fields;

        foreach($fields as &$field) {
            if($field['key'] === $key) {
                return $field;
            }

            if(!empty($field['fields'])) {
                $result = $this->findField($key, $field['fields']);

                if($result !== null) {
                    return $result;
                }
            }

            if(!empty($field['steps'])) {
                $result = $this->findField($key, $field['steps']);

                if($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }

    protected function findFieldProperty($key, $property, $fields = null) {
        $fields = $fields ?? $this->fields;

        foreach($fields as &$field) {
            if($field['key'] === $key) {
                return $field[$property];
            }

            if(!empty($field['fields'])) {
                $result = $this->findFieldProperty($key, $property, $field['fields']);

                if($result !== null) {
                    return $result;
                }
            }

            if(!empty($field['steps'])) {
                $result = $this->findFieldProperty($key, $property, $field['steps']);

                if($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }

    public function render() {
        return view('wireform::components.form');
    }
}
