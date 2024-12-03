<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }"
    >
        <x-filament::input.wrapper>
            <x-filament::input x-bind="NamePicInput"/>
        </x-filament::input.wrapper>
    </div>
</x-dynamic-component>
