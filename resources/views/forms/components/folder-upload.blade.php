<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->

        <input type="file" id="directoryInput" wire:model="{{ $getStatePath() }}.folder"  webkitdirectory multiple>

    </div>
</x-dynamic-component>
