<div class="space-y-4">
    @foreach($fields as $field)
        @php
            $data = $this->findField($field->key);

            if(!empty($field->fields)) {
                $data = array_merge($data, ['fields' => $field->fields]);
            }
        @endphp

        {{ $field->render()->with($data) }}
    @endforeach
</div>
