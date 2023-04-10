<div>
    @foreach($steps as $index => $step)
        <div @class(['hidden' => $index != $activeStep])>
                <div class="space-y-4">
                    @php
                    $data = $this->findField($step->key);

                    if(!empty($step->fields)) {
                        $data = array_merge($data, ['fields' => $step->fields]);
                    }
                @endphp

                {{ $step->render()->with($data) }}
            </div>

            @if($index !== count($steps) - 1)
                <div class="flex mt-4">
                    <x-wireform::button type="button" style="secondary" wire:click="previousStep('{{ $key }}')" @class([
                        'mr-auto',
                        'hidden' => $index == 0
                    ])>
                        {{ __('wireform::step.previous') }}
                    </x-wireform::button>

                    <x-wireform::button type="button" wire:click="nextStep('{{ $key }}')" @class([
                        'ml-auto',
                        'hidden' => $index == count($steps) - 1
                    ])>
                        {{ __('wireform::step.next') }}
                    </x-wireform::button>
                </div>
            @endif
        </div>
    @endforeach

    <x-wireform::modal name="confirmationState" wire:model="stepConfirmationModal" focusable>
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ $confirmationTitle }}
                </h2>

                @if($confirmationDescription)
                    <p class="mt-1 text-gray-500">
                        {{ $confirmationDescription }}
                    </p>
                @endif
            </div>

            @foreach($this->stepConfirmationModalData as $d)
                <div class="mt-4">
                    <div class="font-medium text-gray-500">
                        {{ $d['label'] }}
                    </div>
                    <div class="mt-1 text-gray-900">
                        {{ $d['value'] }}
                    </div>
                </div>
            @endforeach

            <div class="mt-6 flex justify-end">
                <x-wireform::button type="button" style="secondary" wire:click="$toggle('stepConfirmationModal')">
                    {{ __('wireform::step.cancel') }}
                </x-wireform::button>
    
                <x-wireform::button type="button" class="ml-3" wire:click="nextStep('{{ $key }}', true)">
                    {{ __('wireform::step.confirm') }}
                </x-wireform::button>
            </div>
        </div>
    </x-wireform::modal>
</div>
