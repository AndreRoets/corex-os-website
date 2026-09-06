import { useState } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Button, Card } from '../../../Components/UI';
import { longDate, short } from '../../../sast';

export default function RegistrationsIndex({ slug, webinar, registrations, meta, search, totalOnPage }) {
    const currentPage = Number(meta?.current_page ?? 1);
    const lastPage = Number(meta?.last_page ?? 1);
    const total = Number(meta?.total ?? registrations.length);

    const [q, setQ] = useState(search ?? '');

    const runSearch = (e) => {
        e.preventDefault();
        router.get(route('admin.webinars.registrations', slug), q ? { q } : {}, { preserveState: true });
    };

    const joinLink = useForm({
        join_url: webinar?.join_url ?? '',
        join_meeting_id: webinar?.join_meeting_id ?? '',
        join_passcode: webinar?.join_passcode ?? '',
    });

    const recipients = Number(meta?.total ?? 0);

    const submitJoinLink = (e) => {
        e.preventDefault();
        const message = recipients > 0
            ? `This emails the joining link to all ${recipients} people who have registered. It cannot be unsent. Send it now?`
            : 'Nobody has registered yet, so this just saves the link. Continue?';
        if (! confirm(message)) return;

        joinLink.post(route('admin.webinars.join-link', slug));
    };

    return (
        <AdminLayout
            title={webinar?.title ?? 'Registrants'}
            heading={
                <div>
                    <Link href={route('admin.webinars.index')} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                        &larr; All webinars
                    </Link>

                    <div className="mt-2 flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <h1 className="text-2xl font-semibold text-ink">{webinar?.title ?? slug}</h1>
                            <p className="mt-1 text-sm text-[color:var(--color-muted)]">
                                {total} registrant{total === 1 ? '' : 's'}
                                {webinar?.starts_at && <> · {longDate(webinar.starts_at)}</>}
                            </p>
                        </div>

                        <div className="flex flex-wrap gap-3">
                            <Button as="a" href={route('admin.webinars.registrations.download', [slug, 'zoom'])}>Download for Zoom</Button>
                            <Button as="a" href={route('admin.webinars.registrations.download', [slug, 'full'])} variant="secondary">Download full list</Button>
                        </div>
                    </div>
                </div>
            }
        >
            <div className="mb-6 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3 text-xs leading-relaxed text-[color:var(--color-muted)]">
                <strong className="font-medium text-ink">Download for Zoom</strong> gives you the file Zoom&rsquo;s <em>Import from
                CSV</em> expects — upload it under Invitations → Add Registrants and Zoom emails everyone their own joining link.{' '}
                <strong className="font-medium text-ink">Download full list</strong> is the same people with their phone numbers
                and demo-access status, for following up.
            </div>

            <Card className="mb-6 p-5 sm:p-6">
                <div className="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 className="text-sm font-semibold text-ink">Joining link</h2>
                        <p className="mt-1 text-xs text-[color:var(--color-muted)]">
                            Paste your Zoom, Teams or Meet link. It is saved and emailed to everyone who has already registered,
                            and anyone who signs up afterwards gets it automatically.
                        </p>
                    </div>

                    <span className={`inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border px-2.5 py-1 text-xs ${webinar?.join_url ? 'border-[color:var(--color-brand)]/40 text-[color:var(--color-brand-400)]' : 'border-[color:var(--color-border)] text-[color:var(--color-muted)]'}`}>
                        {webinar?.join_url ? 'Link is set' : 'Not set yet'}
                    </span>
                </div>

                <form onSubmit={submitJoinLink} className="mt-4 flex flex-wrap items-start gap-3">
                    <div className="w-full space-y-3">
                        <div>
                            <label htmlFor="join_url" className="mb-1 block text-xs font-medium text-ink">Joining link</label>
                            <input
                                id="join_url" type="url" required value={joinLink.data.join_url}
                                onChange={(e) => joinLink.setData('join_url', e.target.value)}
                                placeholder="https://us06web.zoom.us/j/82437708791?pwd=…"
                                className={`w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${joinLink.errors.join_url ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                            />
                            {joinLink.errors.join_url && <p className="mt-1.5 text-xs text-[#fb7185]">{joinLink.errors.join_url}</p>}
                        </div>

                        <div className="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label htmlFor="join_meeting_id" className="mb-1 block text-xs font-medium text-ink">
                                    Meeting ID <span className="font-normal text-[color:var(--color-faint)]">(optional)</span>
                                </label>
                                <input
                                    id="join_meeting_id" type="text" value={joinLink.data.join_meeting_id}
                                    onChange={(e) => joinLink.setData('join_meeting_id', e.target.value)}
                                    placeholder="824 3770 8791"
                                    className="w-full rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 font-mono text-sm text-ink placeholder:text-[color:var(--color-faint)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]"
                                />
                            </div>
                            <div>
                                <label htmlFor="join_passcode" className="mb-1 block text-xs font-medium text-ink">
                                    Passcode <span className="font-normal text-[color:var(--color-faint)]">(optional)</span>
                                </label>
                                <input
                                    id="join_passcode" type="text" value={joinLink.data.join_passcode}
                                    onChange={(e) => joinLink.setData('join_passcode', e.target.value)}
                                    placeholder="0ABcMc" autoCapitalize="off" autoCorrect="off" spellCheck="false"
                                    className="w-full rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 font-mono text-sm text-ink placeholder:text-[color:var(--color-faint)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]"
                                />
                            </div>
                        </div>

                        <p className="text-xs text-[color:var(--color-faint)]">
                            Copy these straight from your Zoom invitation. The link alone is enough for most people; the Meeting
                            ID and passcode are for anyone joining from the Zoom app.
                        </p>
                    </div>

                    <Button type="submit" disabled={joinLink.processing}>
                        {webinar?.join_url ? 'Resend joining link' : 'Send joining link'}
                        {recipients > 0 && <span className="rounded-full bg-white/20 px-2 py-0.5 text-xs">{recipients}</span>}
                    </Button>
                </form>

                {webinar?.join_url && (
                    <p className="mt-3 text-xs text-[color:var(--color-faint)]">
                        Sending again emails everyone on this list a second time — use it if the link has changed.
                    </p>
                )}
            </Card>

            <form onSubmit={runSearch} className="mb-4 flex flex-wrap items-center gap-3">
                <input
                    type="search" value={q} onChange={(e) => setQ(e.target.value)}
                    placeholder="Search name, email or company…"
                    className="w-64 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-3.5 py-2 text-sm text-ink placeholder:text-[color:var(--color-faint)] focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40"
                />
                <Button type="submit" variant="secondary" size="sm">Search</Button>
                {search !== '' && (
                    <>
                        <Link href={route('admin.webinars.registrations', slug)} className="text-sm text-[color:var(--color-muted)] hover:text-ink transition duration-300">Clear</Link>
                        <span className="text-xs text-[color:var(--color-faint)]">{registrations.length} of {totalOnPage} on this page</span>
                    </>
                )}
            </form>

            {registrations.length === 0 ? (
                <Card className="p-10 text-center">
                    <p className="text-sm text-[color:var(--color-muted)]">
                        {search !== '' ? 'Nobody on this page matches that search.' : 'Nobody has registered yet.'}
                    </p>
                </Card>
            ) : (
                <>
                    <Card className="overflow-x-auto">
                        <table className="w-full min-w-[58rem] text-left text-sm">
                            <thead>
                                <tr className="border-b border-[color:var(--color-border)] text-xs uppercase tracking-wider text-[color:var(--color-faint)]">
                                    <th className="px-5 py-3 font-medium">Name</th>
                                    <th className="px-5 py-3 font-medium">Company</th>
                                    <th className="px-5 py-3 font-medium">Contact</th>
                                    <th className="px-5 py-3 font-medium">Registered</th>
                                    <th className="px-5 py-3 font-medium">Demo access</th>
                                    <th className="px-5 py-3 font-medium">Reminder</th>
                                </tr>
                            </thead>
                            <tbody>
                                {registrations.map((row, i) => {
                                    const name = `${row.first_name ?? ''} ${row.last_name ?? ''}`.trim();
                                    const status = row.demo_access_status ?? null;

                                    return (
                                        <tr key={row.id ?? i} className="border-b border-[color:var(--color-border-soft)] last:border-0">
                                            <td className="px-5 py-4 font-medium text-ink">{name || '—'}</td>
                                            <td className="px-5 py-4 text-[color:var(--color-muted)]">{row.company_name ?? '—'}</td>
                                            <td className="px-5 py-4">
                                                <a href={`mailto:${row.email ?? ''}`} className="text-[color:var(--color-brand-400)] transition duration-300 hover:text-ink">
                                                    {row.email ?? '—'}
                                                </a>
                                                {row.phone && <span className="mt-0.5 block text-xs text-[color:var(--color-faint)]">{row.phone}</span>}
                                            </td>
                                            <td className="px-5 py-4 whitespace-nowrap text-[color:var(--color-muted)]">{short(row.registered_at) ?? '—'}</td>
                                            <td className="px-5 py-4">
                                                {status ? (
                                                    <span className={`inline-flex whitespace-nowrap rounded-full border px-2.5 py-1 text-xs ${status === 'Active' ? 'border-[color:var(--color-brand)]/40 text-[color:var(--color-brand-400)]' : 'border-[color:var(--color-border)] text-[color:var(--color-muted)]'}`}>
                                                        {status}
                                                    </span>
                                                ) : (
                                                    <span className="text-[color:var(--color-faint)]">—</span>
                                                )}
                                                {row.demo_access_ends_at && (
                                                    <span className="mt-1 block text-xs text-[color:var(--color-faint)]">until {longDate(row.demo_access_ends_at)}</span>
                                                )}
                                            </td>
                                            <td className="px-5 py-4 whitespace-nowrap text-xs">
                                                {row.reminder_sent_at ? (
                                                    <span className="text-[color:var(--color-muted)]">Sent {short(row.reminder_sent_at)}</span>
                                                ) : (
                                                    <span className="text-[color:var(--color-faint)]">Not yet</span>
                                                )}
                                            </td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </Card>

                    {lastPage > 1 && (
                        <div className="mt-5 flex items-center justify-between gap-4 text-sm">
                            <span className="text-[color:var(--color-faint)]">Page {currentPage} of {lastPage}</span>
                            <div className="flex gap-3">
                                {currentPage > 1 && (
                                    <Link href={route('admin.webinars.registrations', { slug, page: currentPage - 1, q: search || undefined })} className="text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                                        &larr; Previous
                                    </Link>
                                )}
                                {currentPage < lastPage && (
                                    <Link href={route('admin.webinars.registrations', { slug, page: currentPage + 1, q: search || undefined })} className="text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                                        Next &rarr;
                                    </Link>
                                )}
                            </div>
                        </div>
                    )}
                </>
            )}

            <p className="mt-6 text-xs leading-relaxed text-[color:var(--color-faint)]">
                This list is the only record these people exist in — webinar registrants are deliberately not added to the
                CoreX CRM. Treat the downloads accordingly.
            </p>
        </AdminLayout>
    );
}
