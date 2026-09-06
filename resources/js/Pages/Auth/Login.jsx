import { Head, useForm } from '@inertiajs/react';
import { Button, Field, TextInput } from '../../Components/UI';

export default function Login({ status }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('login.store'));
    };

    return (
        <>
            <Head title="Sign in — CoreX OS admin" />

            <div className="mx-auto max-w-sm py-16 px-5">
                <div className="text-center">
                    <span className="text-2xl font-semibold text-ink">CoreX OS</span>
                    <p className="mt-2 font-mono text-[11px] uppercase tracking-[0.18em] text-[color:var(--color-faint)]">
                        Admin console
                    </p>
                </div>

                {status && (
                    <div className="mt-6 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3 text-sm text-[color:var(--color-muted)]" role="status">
                        {status}
                    </div>
                )}

                <form onSubmit={submit} className="card mt-6 space-y-4 p-6">
                    <Field label="Email" htmlFor="email" error={errors.email}>
                        <TextInput
                            id="email"
                            type="email"
                            required
                            autoFocus
                            autoComplete="username"
                            value={data.email}
                            error={errors.email}
                            onChange={(e) => setData('email', e.target.value)}
                        />
                    </Field>

                    <Field label="Password" htmlFor="password" error={errors.password}>
                        <TextInput
                            id="password"
                            type="password"
                            required
                            autoComplete="current-password"
                            value={data.password}
                            error={errors.password}
                            onChange={(e) => setData('password', e.target.value)}
                        />
                    </Field>

                    <label className="flex items-center gap-2.5 text-sm text-[color:var(--color-muted)]">
                        <input
                            type="checkbox"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            className="rounded border-[color:var(--color-border)] text-[color:var(--color-brand)] focus:ring-[color:var(--color-brand)]/40"
                        />
                        Keep me signed in
                    </label>

                    <Button type="submit" size="lg" className="w-full" disabled={processing}>
                        Sign in
                    </Button>
                </form>

                {/* Said plainly, because someone locked out will look here first and the
                    honest answer is "ask another admin". There is no reset link on purpose:
                    this console can read every registrant's contact details, so an emailed
                    reset would turn knowing an admin's address into a way in. */}
                <p className="mt-5 text-center text-xs leading-relaxed text-[color:var(--color-faint)]">
                    Accounts are created by another admin from the Users screen.
                    Forgotten your password? Ask them to reset it — there is no reset link by design.
                </p>
            </div>
        </>
    );
}
