@php
    use App\Support\Sast;
@endphp

<x-layouts.admin title="Webinars">
    <x-slot:heading>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-ink">Webinars</h1>
                <p class="mt-1 text-sm text-[color:var(--color-muted)]">
                    Create a webinar, hand out its registration link, and see who has signed up.
                </p>
            </div>

            <x-btn href="{{ route('admin.webinars.create') }}" size="md">New webinar</x-btn>
        </div>
    </x-slot:heading>

    @if ($problem)
        <div class="mb-6 rounded-md border border-[#f59e0b]/40 bg-[#f59e0b]/10 px-4 py-3 text-sm text-ink" role="status">
            {{ $problem }}
        </div>
    @endif

    <div class="mb-4 flex items-center justify-between gap-4">
        <p class="text-sm text-[color:var(--color-muted)]">
            {{ count($webinars) }} {{ Str::plural('webinar', count($webinars)) }}
        </p>

        {{-- A link, not a scripted toggle: it survives a page refresh, it can be
             bookmarked, and it works with JavaScript off. --}}
        <a href="{{ route('admin.webinars.index', ['archived' => $includeArchived ? null : 1]) }}"
           class="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
            {{ $includeArchived ? 'Hide archived' : 'Show archived' }}
        </a>
    </div>

    @if (empty($webinars))
        <div class="card p-10 text-center">
            <p class="text-sm text-[color:var(--color-muted)]">
                No webinars yet. Create one and the registration link appears here.
            </p>
        </div>
    @else
        <div class="card overflow-x-auto">
            <table class="w-full min-w-[52rem] text-left text-sm">
                <thead>
                    <tr class="border-b border-[color:var(--color-border)] text-xs uppercase tracking-wider text-[color:var(--color-faint)]">
                        <th scope="col" class="px-5 py-3 font-medium">Webinar</th>
                        <th scope="col" class="px-5 py-3 font-medium">When</th>
                        <th scope="col" class="px-5 py-3 font-medium">Status</th>
                        <th scope="col" class="px-5 py-3 font-medium">Registrants</th>
                        <th scope="col" class="px-5 py-3 font-medium">Registration link</th>
                        <th scope="col" class="px-5 py-3 font-medium text-right">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($webinars as $webinar)
                        @php
                            $slug = $webinar['slug'] ?? '';
                            $archived = (bool) ($webinar['archived'] ?? false);
                            $startsAt = Sast::parse($webinar['starts_at'] ?? null);
                            $count = (int) ($webinar['registration_count'] ?? 0);
                        @endphp

                        <tr class="border-b border-[color:var(--color-border-soft)] last:border-0 {{ $archived ? 'opacity-60' : '' }}">
                            <td class="px-5 py-4">
                                <span class="font-medium text-ink">{{ $webinar['title'] ?? $slug }}</span>
                                <span class="mt-0.5 block font-mono text-xs text-[color:var(--color-faint)]">{{ $slug }}</span>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-[color:var(--color-muted)]">
                                @if ($startsAt)
                                    {{ $startsAt->format('j M Y') }}
                                    <span class="block text-xs text-[color:var(--color-faint)]">
                                        @if (! empty($webinar['duration_minutes']))
                                            {{ $startsAt->format('H:i') }}&ndash;{{ $startsAt->addMinutes((int) $webinar['duration_minutes'])->format('H:i') }} SAST
                                        @else
                                            {{ $startsAt->format('H:i') }} SAST
                                        @endif
                                    </span>
                                @else
                                    &mdash;
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                @php
                                    $open = (bool) ($webinar['registration_open'] ?? false);
                                    $tone = $archived
                                        ? 'border-[color:var(--color-border)] text-[color:var(--color-faint)]'
                                        : ($open
                                            ? 'border-[color:var(--color-brand)]/40 text-[color:var(--color-brand-400)]'
                                            : 'border-[color:var(--color-border)] text-[color:var(--color-muted)]');
                                @endphp
                                <span class="inline-flex whitespace-nowrap rounded-full border px-2.5 py-1 text-xs {{ $tone }}">
                                    {{ $webinar['status_label'] ?? ($archived ? 'Archived' : ($open ? 'Open for registration' : 'Closed')) }}
                                </span>
                            </td>

                            {{-- A labelled button, not a bare number. The count on
                                 its own read as a statistic, so nobody realised
                                 the registrant list was a click away. --}}
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.webinars.registrations', $slug) }}"
                                   class="inline-flex items-center gap-2 whitespace-nowrap rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-3 py-1.5 text-sm text-ink transition duration-300 hover:border-[color:var(--color-brand)] hover:-translate-y-0.5">
                                    <x-icon name="users" class="w-4 h-4 text-[color:var(--color-brand)]" />
                                    View registrants
                                    <span class="rounded-full bg-[color:var(--color-brand)]/15 px-2 py-0.5 text-xs font-medium text-[color:var(--color-brand-400)]">{{ $count }}</span>
                                </a>
                            </td>

                            <td class="px-5 py-4">
                                @if (! empty($webinar['registration_url']))
                                    {{-- The link is the thing this screen exists to
                                         hand out, so copying it is one click and
                                         says so when it worked. It is a public URL
                                         and carries no credential. --}}
                                    <div x-data="{ copied: false }" class="flex items-center gap-2">
                                        <input type="text" readonly
                                               value="{{ $webinar['registration_url'] }}"
                                               @focus="$event.target.select()"
                                               class="w-52 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-2.5 py-1.5 font-mono text-xs text-[color:var(--color-muted)]">

                                        <button type="button"
                                                @click="navigator.clipboard.writeText('{{ $webinar['registration_url'] }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                                class="shrink-0 rounded-md border border-[color:var(--color-border)] px-2.5 py-1.5 text-xs text-[color:var(--color-muted)] transition duration-300 hover:border-[color:var(--color-brand)] hover:text-ink">
                                            <span x-show="!copied">Copy</span>
                                            <span x-show="copied" x-cloak class="text-[color:var(--color-brand-400)]">Copied</span>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-[color:var(--color-faint)]">&mdash;</span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-3 whitespace-nowrap">
                                    <a href="{{ route('admin.webinars.edit', $slug) }}"
                                       class="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">Edit</a>

                                    @unless ($archived)
                                        <form method="POST" action="{{ route('admin.webinars.archive', $slug) }}"
                                              onsubmit="return confirm('Archive this webinar? The registration link stops working immediately — nobody else can sign up or be given demo access. Everyone who already registered keeps theirs.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-[#fb7185]">
                                                Archive
                                            </button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.admin>
