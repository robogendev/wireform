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
            @foreach($choices as $value => $label)
                <div class="block">
                    <input type="radio" id="{{ $key }}-{{ $value }}" value="{{ $value }}" wire:model="data.{{ $key }}" />
                    <label class="font-medium text-sm text-gray-700" for="{{ $key }}-{{ $value }}">{{ $label }}</label>
                </div>
        @endforeach
        </div>
    </div>
@endif
