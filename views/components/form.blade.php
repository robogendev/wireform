<div>
    <form wire:submit.prevent="submit" class="space-y-4">
        @foreach ($this->fields() as $field)
            @php
                $data = $this->findField($field->key);

                if(!empty($field->fields)) {
                    $data = array_merge($data, ['fields' => $field->fields]);
                } else if(!empty($field->steps)) {
                    $data = array_merge($data, ['steps' => $field->steps]);
                }
            @endphp

            {{ $field->render()->with($data) }}
        @endforeach
    </form>
</div>
