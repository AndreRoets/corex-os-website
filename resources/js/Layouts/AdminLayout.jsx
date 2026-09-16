import { Head, Link, router, usePage } from '@inertiajs/react';
import { Alert } from '../Components/UI';

const NAV = [
    ['admin.dashboard', 'Dashboard'],
    ['admin.pages.index', 'Pages'],
    ['admin.marketing.edit', 'Marketing'],
    ['admin.enquiries.index', 'Enquiries'],
    ['admin.sitemap', 'Sitemap'],
    ['admin.users.index', 'Users'],
    ['admin.webinars.index', 'Webinars'],
];

export default function AdminLayout({ title, heading, children }) {
    const { auth, flash } = usePage().props;

    const signOut = (e) => {
        e.preventDefault();
        router.post(route('logout'));
    };

    return (
        <>
            <Head title={title ? `${title} — CoreX OS admin` : 'CoreX OS admin'} />

            <header className="border-b border-[color:var(--color-border)] bg-[color:var(--color-surface)]">
                <div className="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-3.5 sm:px-8">
                    <div className="flex flex-wrap items-center gap-x-6 gap-y-2">
                        <Link href={route('admin.dashboard')} className="font-mono text-[11px] uppercase tracking-[0.18em] text-[color:var(--color-faint)]">
                            CoreX OS admin
                        </Link>

                        <nav className="flex flex-wrap items-center gap-4">
                            {NAV.map(([name, label]) => (
                                <Link
                                    key={name}
                                    href={route(name)}
                                    className={`text-sm transition duration-300 ${
                                        route().current(name) || route().current(name + '.*')
                                            ? 'text-ink font-medium'
                                            : 'text-[color:var(--color-muted)] hover:text-ink'
                                    }`}
                                >
                                    {label}
                                </Link>
                            ))}
                        </nav>
                    </div>

                    <div className="flex items-center gap-4">
                        {auth?.user && <span className="hidden text-xs text-[color:var(--color-faint)] sm:inline">{auth.user.email}</span>}
                        <button onClick={signOut} className="text-xs font-medium text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                            Sign out
                        </button>
                    </div>
                </div>
            </header>

            <main className="mx-auto max-w-7xl px-5 py-8 sm:px-8 sm:py-10">
                {heading && <div className="mb-6">{heading}</div>}

                <Alert tone="status">{flash?.status}</Alert>
                <Alert tone="error">{flash?.error}</Alert>

                {children}
            </main>

            <footer className="mx-auto max-w-7xl px-5 pb-10 sm:px-8">
                <p className="border-t border-[color:var(--color-border)] pt-5 text-xs text-[color:var(--color-faint)]">
                    Webinars, registrants and demo access all live in CoreX OS. This console reads and writes them
                    live — none of that is stored on the website. Contact enquiries are the one exception: they are
                    the site's own, so their source can be tracked.
                </p>
            </footer>
        </>
    );
}
