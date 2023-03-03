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

        <textarea
            class="block w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100"
            id="{{ $key }}"
            rows="5"
            {{ $disabled ? 'disabled' : '' }}
            wire:model="data.{{ $key }}"
        ></textarea>

        @error("data.{$key}")
            <x-wireform::input-error :messages="$message" />
        @enderror
    </div>
@endif
