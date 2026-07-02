<x-layouts.app>
    <x-sections.hero />
    <x-sections.commitment />
    <x-sections.problem />
    <x-collapsible target="pillars" label="Pillars" icon="layers">
        <x-sections.pillars />
    </x-collapsible>
    <x-collapsible target="flows" label="Flows" icon="workflow">
        <x-sections.flows />
    </x-collapsible>
    <x-collapsible target="ellie" label="Ellie AI" icon="sparkles">
        <x-sections.ellie />
    </x-collapsible>

    {{-- Mid-page CTA: convert here, before the deep reference sections. --}}
    <x-sections.cta />

    {{-- Reference-heavy sections — collapsed by default on desktop (deep). --}}
    <x-collapsible target="features" label="Modules" icon="database" deep>
        <x-sections.features />
    </x-collapsible>
    <x-collapsible target="compliance" label="Compliance" icon="shield-check" deep>
        <x-sections.compliance />
    </x-collapsible>
    <x-collapsible target="control" label="Control" icon="lock" deep>
        <x-sections.control />
    </x-collapsible>
    <x-sections.open-development />
    <x-sections.demo />
</x-layouts.app>
