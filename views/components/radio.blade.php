@if($visibility)
    <div class="space-y-1">
        @if($label || $description)
            <div>
                @if($label)
                    <x-wireform::label for="{{ $key }}" :value="$label" />
                @endif

                @if($description)
                    <p class="text-gray-500">{{ $description }}</p>
                @endif
            </div>
        @endif

        <div>
            @foreach($choices as $value => $label)
                <div class="block">
                    <input
                        class="disabled:bg-gray-100"
                        id="{{ $key }}-{{ $value }}"
                        type="radio"
                        value="{{ $value }}"
                        {{ $disabled ? 'disabled' : '' }}
                        wire:model="data.{{ $key }}"
                    />
                    <label
                        @class([
                            "font-medium",
                            "text-gray-700" => !$disabled,
                            "text-gray-500" => $disabled,
                        ])
                        for="{{ $key }}-{{ $value }}"
                    >{{ $label }}</label>
                </div>
            @endforeach
        </div>

        @error("data.{$key}")
            <x-wireform::input-error :messages="$message" />
        @enderror
    </div>
@endif
