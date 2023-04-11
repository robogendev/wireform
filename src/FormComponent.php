<?php

namespace RoboGen\Wireform;

use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FormComponent extends Component {
    public $fields;
    public $data = [];
    public $cleanData = [];
    public $rules;

    public $stepConfirmationModal = false;
    public $stepConfirmationModalData = [];

    public function mount() {
        $this->fields = json_decode(json_encode($this->fields()), true);

        $this->buildData();
        $this->fields = $this->handleConditionals();
        $this->buildRules();    
    }

    public function updated() {        
        $this->fields = $this->handleConditionals();
    }

    public function submit() {
        $this->validate();

        $this->data = Arr::undot(Arr::whereNotNull(Arr::dot($this->data)));
        $this->cleanData = $this->buildCleanData();

        $this->emit('formSubmitted', $this->data, $this->cleanData);
    }

    public function buildData(?array $fields = null): void {
        $fields = $fields ?? $this->fields;

        foreach ($fields as $field) {
            if(!empty($field['fields'])) {
                $this->buildData($field['fields']);
            } else if(!empty($field['steps'])) {
                $this->buildData($field['steps'], true);
            } else {
                if($field['type'] === 'checkbox') {
                    Arr::set($this->data, $field['key'], []);
                } else {
                    Arr::set($this->data, $field['key'], $field['value']);
                }
            }
        }
    }

    public function buildCleanData(): array {
        $data = [];

        if(empty($this->data)) {
            return $data;
        }

        foreach(Arr::dot($this->data) as $key => $value) {

            // If key includes [] then it's an array
            if(Str::contains($key, '[]')) {
                $key = Str::before($key, '[]') . '[]';
            }

            $type = $this->findFieldProperty($key, 'type');
            $label = $this->findFieldProperty($key, 'label');
            $include = $this->findFieldProperty($key, 'include');

            if(empty($label) || empty($value) || $type === 'hidden' || $include === false) {
                continue;
            }

            if($this->findFieldProperty($key, 'type') === 'radio') {
                $choices = $this->findFieldProperty($key, 'choices');
                $value = $choices[$value];
            }

            if($this->findFieldProperty($key, 'type') === 'select') {
                $options = $this->findFieldProperty($key, 'options');
                $value = $options[$value];
            }

            if($this->findFieldProperty($key, 'type') === 'checkbox') {
                $choices = $this->findFieldProperty($key, 'choices');
                $value = $choices[$value];
            }

            if(array_key_exists($key, $data)) {
                $data[$key]['value'] .= ', ' . $value;
                continue;
            }

            $data[$key] = [
                'label' => $label,
                'value' => $value,
            ];
        }

        return $data;
    }

    public function buildRules(?array $fields = null): void {
        $fields = $fields ?? $this->fields;

        foreach ($fields as $field) {
            if(!empty($field['fields'])) {
                $this->buildRules($field['fields']);
            } else if(!empty($field['steps'])) {
                $this->buildRules($field['steps']);
            } else if(!empty($field['required']) && !empty($field['visibility'])) {
                $this->rules["data.{$field['key']}"] = 'required';
            }
        }
    }

    public function handleConditionals(?array $fields = null) : array {
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
        if (empty($field['logic'])) {
            return $field;
        }

        $relation = 'AND';
        $conditionals = [];

        $visibility = $field['visibility'];
        $data = Arr::dot($this->data ?? []);
    
        foreach ($field['logic'] as $conditional) {
            if(!is_array($conditional)) {
                $relation = $conditional;
                continue;
            }

            $key = $conditional[0]['key'];
            $operator = $conditional[1];
            $value = $conditional[2];

            if (array_key_exists($key, $data)) {
                $match = ($data[$key] === $value);
                if (($match && $operator === '==') || (!$match && $operator === '!=')) {
                    $conditionals[] = true;
                } else {
                    $conditionals[] = false;
                }
            } else {
                $conditionals[] = ($operator === '!=');
            }
        }

        if ($relation === 'OR') {
            $visibility = in_array(true, $conditionals);
        } else {
            $visibility = !in_array(false, $conditionals);
        }
    
        if(!$visibility) {
            if($field['type'] === 'checkbox') {
                Arr::set($this->data, $field['key'], []);
            } else {
                Arr::set($this->data, $field['key'], null);
            }
        }

        $field['visibility'] = $visibility;

        return $field;
    }
    

    public function nextStep($key, $skip_confirmation = false) {
        $confirm = $this->findFieldProperty($key, 'confirmation');

        $this->validateStep($key);

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

    public function validateStep($key): void {
        $step = $this->findFieldProperty($key, 'steps')[$this->findFieldProperty($key, 'activeStep')];
        $rules = [];

        foreach($step['fields'] as $field) {
            if(!empty($field['required']) && !empty($field['visibility'])) {
                $rules["data.{$field['key']}"] = 'required';
            }
        }

        if(empty($rules)) {
            return;
        }

        $this->validate($rules);
    }

    private function buildStepConfirmationModalData($key) {
        $step = $this->findFieldProperty($key, 'steps')[$this->findFieldProperty($key, 'activeStep')];
        $data = [];

        if(empty($this->data)) {
            return $data;
        }

        foreach(Arr::dot($this->data) as $key => $value) {

            // If key includes [] then it's an array
            if(Str::contains($key, '[]')) {
                $key = Str::before($key, '[]') . '[]';
            }

            $type = $this->findFieldProperty($key, 'type', $step['fields']);
            $label = $this->findFieldProperty($key, 'label', $step['fields']);

            if(empty($label) || empty($value) || $type === 'hidden') {
                continue;
            }

            if($this->findFieldProperty($key, 'type', $step['fields']) === 'radio') {
                $choices = $this->findFieldProperty($key, 'choices', $step['fields']);
                $value = $choices[$value];
            }

            if($this->findFieldProperty($key, 'type', $step['fields']) === 'select') {
                $options = $this->findFieldProperty($key, 'options', $step['fields']);
                $value = $options[$value];
            }

            if($this->findFieldProperty($key, 'type', $step['fields']) === 'checkbox') {
                $choices = $this->findFieldProperty($key, 'choices', $step['fields']);
                $value = $choices[$value];
            }

            if(array_key_exists($key, $data)) {
                $data[$key]['value'] .= ', ' . $value;
                continue;
            }

            $data[$key] = [
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
