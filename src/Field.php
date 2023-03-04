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
    public $logic;

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

    public function set_logic(array $logic) {
        if(!empty($logic['relation']) && !in_array($logic['relation'], ['AND', 'OR'])) {
            throw new \Exception('Invalid relation type');
        }

        $this->logic = $logic;
        return $this;
    }

    public function get_logic(): ?array {
        return $this->logic;
    }

    public function render(): View {
        return view("wireform::components.{$this->type}");
    }
}
