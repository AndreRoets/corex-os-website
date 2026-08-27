@php
    use App\Support\Sast;

    $isEdit = $webinar !== null;

    $fieldBase = 'w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]';

    // Old input wins (a failed save must not throw away what was typed), then
    // what CoreX gave us, then the default.
    $value = function (string $field, $default = null) use ($webinar) {
        return old($field, $webinar[$field] ?? $default);
    };
@endphp

<x-layouts.admin :title="$isEdit ? 'Edit webinar' : 'New webinar'">
    <x-slot:heading>
        <div>
            <a href="{{ route('admin.webinars.index') }}" class="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                &larr; All webinars
            </a>
            <h1 class="mt-2 text-2xl font-semibold text-ink">
                {{ $isEdit ? 'Edit webinar' : 'New webinar' }}
            </h1>
        </div>
    </x-slot:heading>

    <div class="max-w-2xl">

        {{-- The one thing an editor genuinely needs to know before changing a
             date. CoreX holds each registrant to the deadline they were emailed;
             shortening access somebody was already promised would be worse than
             two dates differing, so it deliberately does not do that. --}}
        @if ($isEdit && $registrationCount > 0)
            <div class="mb-6 rounded-md border border-[#f59e0b]/40 bg-[#f59e0b]/10 px-4 py-3.5 text-sm leading-relaxed text-ink" role="status">
                <p>
                    People have already registered. Changing the date or the access window applies to
                    anyone who signs up from now on — those already registered keep the end date they
                    were given in their email.
                </p>
                <p class="mt-1.5 text-xs text-[color:var(--color-muted)]">
                    {{ $registrationCount }} {{ Str::plural('registration', $registrationCount) }} so far.
                </p>
            </div>
        @endif

        @if ($isEdit && ! empty($unknownFields))
            <div class="mb-6 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3.5 text-sm leading-relaxed text-[color:var(--color-muted)]" role="status">
                CoreX did not send back
                {{ collect($unknownFields)->map(fn ($f) => str_replace('_', ' ', $f))->join(', ', ' or ') }},
                so those are shown blank below and will be <strong class="text-ink">left exactly as they are</strong>
                when you save. Nothing is cleared by saving this form.
            </div>
        @endif

        <form method="POST"
              action="{{ $isEdit ? route('admin.webinars.update', $slug) : route('admin.webinars.store') }}"
              class="card space-y-6 p-6 sm:p-8">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            {{-- Fields CoreX could not tell us the current value of. Naming them
                 here keeps them out of the save entirely, so an empty input can
                 never silently wipe a joining link nobody meant to touch. --}}
            @foreach ($unknownFields ?? [] as $unknown)
                <input type="hidden" name="_unknown[]" value="{{ $unknown }}">
            @endforeach

            <div>
                <label for="title" class="block text-sm font-medium text-ink">Title</label>
                <p class="mt-1 text-xs text-[color:var(--color-muted)]">Registrants see this in their confirmation email and calendar.</p>
                <input id="title" name="title" type="text" value="{{ $value('title') }}" required
                       class="{{ $fieldBase }} mt-2 @error('title') border-[#e11d48] @else border-[color:var(--color-border)] @enderror"
                       placeholder="CoreX OS — a walkthrough for agency principals">
                @error('title') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-ink">Link name</label>
                <p class="mt-1 text-xs text-[color:var(--color-muted)]">The short name in the registration web address. Leave blank to build it from the title.</p>
                <input id="slug" name="slug" type="text" value="{{ $value('slug', '') }}"
                       class="{{ $fieldBase }} mt-2 font-mono @error('slug') border-[#e11d48] @else border-[color:var(--color-border)] @enderror"
                       placeholder="corex-walkthrough-sept">
                @error('slug') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-ink">What you&rsquo;ll cover</label>
                <p class="mt-1 text-xs text-[color:var(--color-muted)]">Shown on the registration page and in the confirmation email.</p>
                <textarea id="description" name="description" rows="4"
                          class="{{ $fieldBase }} mt-2 resize-y @error('description') border-[#e11d48] @else border-[color:var(--color-border)] @enderror"
                          placeholder="Everything a principal needs to see, in 45 minutes.">{{ $value('description', '') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
            </div>

            {{-- One date, then a start and a finish time. A webinar happens on a
                 single day, so asking for the date twice only creates ways to
                 get it wrong — an end on the wrong day, or "12:00 AM" (midnight)
                 read as earlier than a 10:00 AM start. CoreX stores a start plus
                 a duration; the controller joins these three and converts. --}}
            <div x-data="{
                     start: @js(old('start_time', Sast::timeForInput($webinar['starts_at'] ?? null))),
                     end: @js(old('end_time', Sast::endTimeForInput($webinar['starts_at'] ?? null, $webinar['duration_minutes'] ?? 60))),
                     syncEnd() {
                         if (! this.start) return;
                         // Only ever fill a blank or now-impossible finish time —
                         // never overwrite one that was set deliberately.
                         if (this.end && this.end > this.start) return;
                         const [h, m] = this.start.split(':').map(Number);
                         if (isNaN(h) || isNaN(m)) return;
                         const mins = (h * 60 + m + 60) % (24 * 60);
                         const p = (n) => String(n).padStart(2, '0');
                         this.end = `${p(Math.floor(mins / 60))}:${p(mins % 60)}`;
                     },
                 }">
                <label for="date" class="block text-sm font-medium text-ink">Date and time</label>
                <p class="mt-1 text-xs text-[color:var(--color-muted)]">
                    South African time. Registration closes automatically when the webinar starts, and the
                    finishing time is what shows in the calendar invite.
                </p>

                <div class="mt-2 grid gap-4 sm:grid-cols-[1.4fr_1fr_1fr]">
                    <div>
                        {{-- These inputs carry no timezone. What is typed is read
                             as SAST and sent to CoreX with an explicit +02:00 —
                             see App\Support\Sast. --}}
                        <input id="date" name="date" type="date" required
                               value="{{ old('date', Sast::dateForInput($webinar['starts_at'] ?? null)) }}"
                               class="{{ $fieldBase }} @error('date') border-[#e11d48] @else border-[color:var(--color-border)] @enderror">
                        <span class="mt-1 block text-xs text-[color:var(--color-faint)]">Date</span>
                        @error('date') <p class="mt-1 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="start_time" class="sr-only">Starting time</label>
                        <input id="start_time" name="start_time" type="time" required
                               x-model="start" @change="syncEnd()"
                               class="{{ $fieldBase }} @error('start_time') border-[#e11d48] @else border-[color:var(--color-border)] @enderror">
                        <span class="mt-1 block text-xs text-[color:var(--color-faint)]">Starts</span>
                        @error('start_time') <p class="mt-1 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="end_time" class="sr-only">Finishing time</label>
                        <input id="end_time" name="end_time" type="time" required
                               x-model="end"
                               class="{{ $fieldBase }} @error('end_time') border-[#e11d48] @else border-[color:var(--color-border)] @enderror">
                        <span class="mt-1 block text-xs text-[color:var(--color-faint)]">Ends</span>
                        @error('end_time') <p class="mt-1 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- The joining link is not set here any more. It lives on the
                 registrants screen, behind "Send the joining link", because
                 pasting it there does the thing you actually want at that
                 moment: save it AND email it to everyone already signed up.
                 Setting it quietly on this form would leave the people who
                 registered before you had the link with a confirmation email
                 that has no link in it, and nothing to tell you so. --}}

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="access_ends_days_after" class="block text-sm font-medium text-ink">Demo access ends this many days after</label>
                    <p class="mt-1 text-xs text-[color:var(--color-muted)]">Everyone who registers loses their demo login at the end of that day — whether or not they used it. Enter 0 to end it on the day of the webinar.</p>
                    <input id="access_ends_days_after" name="access_ends_days_after" type="number" min="0" step="1"
                           value="{{ $value('access_ends_days_after', 3) }}"
                           class="{{ $fieldBase }} mt-2 @error('access_ends_days_after') border-[#e11d48] @else border-[color:var(--color-border)] @enderror">
                    @error('access_ends_days_after') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="reminder_hours_before" class="block text-sm font-medium text-ink">Send the reminder this many hours before</label>
                    <p class="mt-1 text-xs text-[color:var(--color-muted)]">One reminder per person, with the joining link.</p>
                    <input id="reminder_hours_before" name="reminder_hours_before" type="number" min="0" step="1"
                           value="{{ $value('reminder_hours_before', 24) }}"
                           class="{{ $fieldBase }} mt-2 @error('reminder_hours_before') border-[#e11d48] @else border-[color:var(--color-border)] @enderror">
                    @error('reminder_hours_before') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 border-t border-[color:var(--color-border)] pt-6">
                <x-btn type="submit" size="md">{{ $isEdit ? 'Save changes' : 'Create webinar' }}</x-btn>
                <x-btn href="{{ route('admin.webinars.index') }}" variant="ghost" size="md">Cancel</x-btn>
            </div>
        </form>
    </div>
</x-layouts.admin>
