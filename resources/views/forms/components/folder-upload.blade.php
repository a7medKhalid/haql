<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{
        state: $wire.$entangle('{{ $getStatePath() }}'),
        filesNames: $wire.$entangle('{{ $getStatePath() }}'),
        handleFiles() {
                        console.log($refs.directoryInput.files);


{{--            this.filesNames = Array.from($refs.directoryInput.files).map(file => file.webkitRelativePath);--}}
            this.filesNames = [];

            for (let i = 0; i < $refs.directoryInput.files.length; i++) {
                let file = $refs.directoryInput.files[i];
                this.filesNames.push(file.webkitRelativePath);
            }
                        console.log(this.filesNames);


        }
    }">

        <input type="file" x-ref="directoryInput" x-on:change="handleFiles()" id="directoryInput" wire:model="{{ $getStatePath() }}.files[]"  webkitdirectory multiple>
        <div wire:loading wire:target="{{ $getStatePath() }}.folder[]">Uploading...</div>


    </div>


</x-dynamic-component>

