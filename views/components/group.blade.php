<div>
    @if($label || $description)
        <div class="mb-6">
            @if($label)
                <h2 class="text-lg leading-6 font-medium text-gray-900">{{ $label }}</h2>
            @endif

            @if($description)
                <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
            @endif
        </div>
    @endif

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
</div>
