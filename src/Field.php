<?php

namespace RoboGen\Wireform;

use Illuminate\View\View;

class Field {
    public $type;
    public $key;
    public $label;
    public $description;
    public $disabled;
    public $visibility = true;
    public $required;
    public $value;
    public $conditionals;

    public function __construct(string $key, ?string $label = null) {
        $this->key = $key;
        $this->label = $label;
    }

    public function set_key(string $key): self {
        $this->key = $key;
        return $this;
    }

    public function get_key(): string {
        return $this->key;
    }

    public function set_label(string $label): self {
        $this->label = $label;
        return $this;
    }

    public function get_label(): ?string {
        return $this->label;
    }

    public function set_description(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function get_description(): ?string {
        return $this->description;
    }

    public function set_disabled(bool $disabled): self {
        $this->disabled = $disabled;
        return $this;
    }

    public function get_disabled(): bool {
        return $this->disabled;
    }

    public function set_visibility(bool $visibility): self {
        $this->visibility = $visibility;
        return $this;
    }

    public function get_visibility(): bool {
        return $this->visibility;
    }

    public function set_required(bool $required): self {
        $this->required = $required;
        return $this;
    }

    public function get_required(): bool {
        return $this->required;
    }

    public function set_value($value): self {
        $this->value = $value;
        return $this;
    }

    public function get_value() {
        return $this->value;
    }

    public function add_conditional(Field $field, string $operator, $value): self {
        $this->conditionals[] = [
            'field' => $field,
            'operator' => $operator,
            'value' => $value
        ];
        return $this;
    }

    public function add_conditionals(array $conditionals): self {
        foreach ($conditionals as $conditional) {
            $this->conditionals[] = $conditional;
        }
        return $this;
    }

    public function get_conditionals(): ?array {
        return $this->conditionals;
    }

    public function render(): View {
        return view("wireform::components.{$this->type}");
    }
}
