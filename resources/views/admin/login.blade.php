@php
    $fieldBase = 'w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]';
@endphp

<x-layouts.admin title="Sign in">
    <div class="mx-auto max-w-sm py-10">
        <div class="text-center">
            <x-logo class="text-2xl" />
            <p class="mt-2 font-mono text-[11px] uppercase tracking-[0.18em] text-[color:var(--color-faint)]">
                Webinar console
            </p>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3 text-sm text-[color:var(--color-muted)]" role="status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="card mt-6 space-y-4 p-6">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-ink">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="{{ $fieldBase }} @error('email') border-[#e11d48] @else border-[color:var(--color-border)] @enderror">
                @error('email') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-ink">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="{{ $fieldBase }} @error('password') border-[#e11d48] @else border-[color:var(--color-border)] @enderror">
                @error('password') <p class="mt-1.5 text-xs text-[#fb7185]">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2.5 text-sm text-[color:var(--color-muted)]">
                <input type="checkbox" name="remember" value="1" class="rounded border-[color:var(--color-border)] text-[color:var(--color-brand)] focus:ring-[color:var(--color-brand)]/40">
                Keep me signed in
            </label>

            <x-btn type="submit" size="lg" class="w-full">Sign in</x-btn>
        </form>

        {{-- Said plainly, because someone locked out will look here first and
             the honest answer is "ask whoever runs the server". There is no
             reset link on purpose: this console can read every registrant's
             contact details, so an emailed reset would turn knowing an admin's
             address into a way in. --}}
        <p class="mt-5 text-center text-xs leading-relaxed text-[color:var(--color-faint)]">
            Accounts are created by whoever administers this site.
            Forgotten your password? Ask them to reset it — there is no reset link by design.
        </p>
    </div>
</x-layouts.admin>
