@php
    use App\Support\Sast;

    $currentPage = (int) ($meta['current_page'] ?? 1);
    $lastPage = (int) ($meta['last_page'] ?? 1);
    $total = (int) ($meta['total'] ?? count($registrations));
@endphp

<x-layouts.admin :title="($webinar['title'] ?? 'Registrants')">
    <x-slot:heading>
        <div>
            <a href="{{ route('admin.webinars.index') }}" class="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                &larr; All webinars
            </a>

            <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-ink">{{ $webinar['title'] ?? $slug }}</h1>
                    <p class="mt-1 text-sm text-[color:var(--color-muted)]">
                        {{ $total }} {{ Str::plural('registrant', $total) }}
                        @if ($startsAt = Sast::parse($webinar['starts_at'] ?? null))
                            · {{ $startsAt->format('j M Y, H:i') }} SAST
                        @endif
                    </p>
                </div>

                {{-- The reason this screen exists. The Zoom file goes straight
                     into Zoom → the webinar → Invitations → Add Registrants →
                     Import from CSV, and Zoom mails everyone their own unique
                     join link. Both files come from CoreX byte-for-byte. --}}
                <div class="flex flex-wrap gap-3">
                    <x-btn href="{{ route('admin.webinars.registrations.download', [$slug, 'zoom']) }}" size="md">
                        <x-icon name="file-text" class="w-4 h-4" />
                        Download for Zoom
                    </x-btn>
                    <x-btn href="{{ route('admin.webinars.registrations.download', [$slug, 'full']) }}" variant="secondary" size="md">
                        <x-icon name="file-text" class="w-4 h-4" />
                        Download full list
                    </x-btn>
                </div>
            </div>
        </div>
    </x-slot:heading>

    <div class="mb-6 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3 text-xs leading-relaxed text-[color:var(--color-muted)]">
        <strong class="font-medium text-ink">Download for Zoom</strong> gives you the file Zoom&rsquo;s
        <em>Import from CSV</em> expects — upload it under Invitations → Add Registrants and Zoom emails
        everyone their own joining link.
        <strong class="font-medium text-ink">Download full list</strong> is the same people with their
        phone numbers and demo-access status, for following up.
    </div>

    {{-- Search. Scoped to this page and labelled as such — filtering here while
         implying it had searched all 300 sign-ups would be a quiet lie on the
         one screen where "they're not on the list" is a decision people act on. --}}
    <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
        @if ($currentPage > 1)
            <input type="hidden" name="page" value="{{ $currentPage }}">
        @endif
        <input type="search" name="q" value="{{ $search }}"
               placeholder="Search name, email or company&hellip;"
               class="w-64 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-3.5 py-2 text-sm text-ink placeholder:text-[color:var(--color-faint)] focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40">
        <x-btn type="submit" variant="secondary" size="sm">Search</x-btn>
        @if ($search !== '')
            <a href="{{ route('admin.webinars.registrations', $slug) }}" class="text-sm text-[color:var(--color-muted)] hover:text-ink transition duration-300">Clear</a>
            <span class="text-xs text-[color:var(--color-faint)]">
                {{ count($registrations) }} of {{ $totalOnPage }} on this page
            </span>
        @endif
    </form>

    @if (empty($registrations))
        <div class="card p-10 text-center">
            <p class="text-sm text-[color:var(--color-muted)]">
                {{ $search !== '' ? 'Nobody on this page matches that search.' : 'Nobody has registered yet.' }}
            </p>
        </div>
    @else
        <div class="card overflow-x-auto">
            <table class="w-full min-w-[58rem] text-left text-sm">
                <thead>
                    <tr class="border-b border-[color:var(--color-border)] text-xs uppercase tracking-wider text-[color:var(--color-faint)]">
                        <th scope="col" class="px-5 py-3 font-medium">Name</th>
                        <th scope="col" class="px-5 py-3 font-medium">Company</th>
                        <th scope="col" class="px-5 py-3 font-medium">Contact</th>
                        <th scope="col" class="px-5 py-3 font-medium">Registered</th>
                        <th scope="col" class="px-5 py-3 font-medium">Demo access</th>
                        <th scope="col" class="px-5 py-3 font-medium">Reminder</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($registrations as $row)
                        @php
                            $name = trim(($row['first_name'] ?? '').' '.($row['last_name'] ?? ''));
                            $status = $row['demo_access_status'] ?? null;
                            $accessEnds = Sast::parse($row['demo_access_ends_at'] ?? null);
                            $reminderSent = Sast::parse($row['reminder_sent_at'] ?? null);
                        @endphp

                        <tr class="border-b border-[color:var(--color-border-soft)] last:border-0">
                            <td class="px-5 py-4 font-medium text-ink">{{ $name !== '' ? $name : '—' }}</td>

                            <td class="px-5 py-4 text-[color:var(--color-muted)]">{{ $row['company_name'] ?? '—' }}</td>

                            <td class="px-5 py-4">
                                <a href="mailto:{{ $row['email'] ?? '' }}" class="text-[color:var(--color-brand-400)] transition duration-300 hover:text-ink">
                                    {{ $row['email'] ?? '—' }}
                                </a>
                                @if (! empty($row['phone']))
                                    <span class="mt-0.5 block text-xs text-[color:var(--color-faint)]">{{ $row['phone'] }}</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-[color:var(--color-muted)]">
                                {{ Sast::short($row['registered_at'] ?? null) ?? '—' }}
                            </td>

                            <td class="px-5 py-4">
                                @if ($status)
                                    <span class="inline-flex whitespace-nowrap rounded-full border px-2.5 py-1 text-xs
                                        {{ $status === 'Active'
                                            ? 'border-[color:var(--color-brand)]/40 text-[color:var(--color-brand-400)]'
                                            : 'border-[color:var(--color-border)] text-[color:var(--color-muted)]' }}">
                                        {{ $status }}
                                    </span>
                                @else
                                    <span class="text-[color:var(--color-faint)]">—</span>
                                @endif

                                @if ($accessEnds)
                                    <span class="mt-1 block text-xs text-[color:var(--color-faint)]">
                                        until {{ $accessEnds->format('j M Y') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-xs">
                                @if ($reminderSent)
                                    <span class="text-[color:var(--color-muted)]">Sent {{ $reminderSent->format('j M, H:i') }}</span>
                                @else
                                    <span class="text-[color:var(--color-faint)]">Not yet</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($lastPage > 1)
            <div class="mt-5 flex items-center justify-between gap-4 text-sm">
                <span class="text-[color:var(--color-faint)]">Page {{ $currentPage }} of {{ $lastPage }}</span>

                <div class="flex gap-3">
                    @if ($currentPage > 1)
                        <a href="{{ route('admin.webinars.registrations', [$slug, 'page' => $currentPage - 1, 'q' => $search ?: null]) }}"
                           class="text-[color:var(--color-muted)] transition duration-300 hover:text-ink">&larr; Previous</a>
                    @endif
                    @if ($currentPage < $lastPage)
                        <a href="{{ route('admin.webinars.registrations', [$slug, 'page' => $currentPage + 1, 'q' => $search ?: null]) }}"
                           class="text-[color:var(--color-muted)] transition duration-300 hover:text-ink">Next &rarr;</a>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <p class="mt-6 text-xs leading-relaxed text-[color:var(--color-faint)]">
        This list is the only record these people exist in — webinar registrants are deliberately not
        added to the CoreX CRM. Treat the downloads accordingly.
    </p>
</x-layouts.admin>
