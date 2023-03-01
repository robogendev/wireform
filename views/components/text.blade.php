@if($visibility)
    <div class="space-y-1">
        @if($label || $description)
            <div>
                @if($label)
                    <x-wireform::label for="{{ $key }}" :value="$label" />
                @endif

                @if($description)
                    <p class="text-sm text-gray-500">{{ $description }}</p>
                @endif
            </div>
        @endif

        <input type="text" id="{{ $key }}" wire:model="data.{{ $key }}" class="block w-full border-gray-300 rounded-md shadow-sm" />
    </div>
@endif
