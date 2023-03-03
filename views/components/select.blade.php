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

        <div>
            <select
                class="block w-full border-gray-300 rounded-md shadow-sm disabled:bg-gray-100"    
                id="{{ $key }}-{{ $value }}"
                {{ $disabled ? 'disabled' : '' }}
                wire:model="data.{{ $key }}"
            >
                @foreach($options as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        @error("data.{$key}")
            <x-wireform::input-error :messages="$message" />
        @enderror
    </div>
@endif
