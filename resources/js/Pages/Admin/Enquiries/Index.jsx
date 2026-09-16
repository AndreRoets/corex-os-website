import { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Badge, Button, Card } from '../../../Components/UI';
import { short } from '../../../sast';

export default function EnquiriesIndex({ enquiries, meta, search, channels, channelWindowDays }) {
    const currentPage = Number(meta?.current_page ?? 1);
    const lastPage = Number(meta?.last_page ?? 1);
    const total = Number(meta?.total ?? enquiries.length);

    const [q, setQ] = useState(search ?? '');

    const query = (page) => {
        const params = {};
        if (q) params.q = q;
        if (page && page > 1) params.page = page;
        return params;
    };

    const runSearch = (e) => {
        e.preventDefault();
        router.get(route('admin.enquiries.index'), query(), { preserveState: true });
    };

    const clearSearch = () => {
        setQ('');
        router.get(route('admin.enquiries.index'), {}, { preserveState: true });
    };

    const channelTotal = channels.reduce((sum, c) => sum + c.count, 0);

    return (
        <AdminLayout
            title="Enquiries"
            heading={
                <div className="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-semibold text-ink">Enquiries</h1>
                        <p className="mt-1 text-sm text-[color:var(--color-muted)]">
                            {total} message{total === 1 ? '' : 's'} sent through the contact page
                            {search ? <> matching &ldquo;{search}&rdquo;</> : null}.
                        </p>
                    </div>
                    <Button as="a" href={route('admin.enquiries.download', search ? { q: search } : {})} variant="secondary">
                        Download CSV
                    </Button>
                </div>
            }
        >
            <Card className="mb-6 p-5 sm:p-6">
                <div className="flex flex-wrap items-baseline justify-between gap-2">
                    <h2 className="text-sm font-semibold text-ink">Where enquiries came from</h2>
                    <span className="text-xs text-[color:var(--color-faint)]">Last {channelWindowDays} days · {channelTotal} enquir{channelTotal === 1 ? 'y' : 'ies'}</span>
                </div>

                {channels.length === 0 ? (
                    <p className="mt-3 text-sm text-[color:var(--color-muted)]">Nothing yet. Channels are worked out from the UTM tags, click IDs and referrer captured on each visitor&rsquo;s first page.</p>
                ) : (
                    <ul className="mt-4 space-y-2">
                        {channels.map((c) => {
                            const pct = channelTotal ? Math.round((c.count / channelTotal) * 100) : 0;
                            return (
                                <li key={c.label} className="grid grid-cols-[9rem_1fr_3rem] items-center gap-3 text-sm">
                                    <span className="text-ink">{c.label}</span>
                                    <span className="h-2 overflow-hidden rounded-full bg-[color:var(--color-surface-2)]">
                                        <span className="block h-full rounded-full bg-[color:var(--color-brand)]" style={{ width: `${pct}%` }} />
                                    </span>
                                    <span className="text-right tabular-nums text-[color:var(--color-muted)]">{c.count}</span>
                                </li>
                            );
                        })}
                    </ul>
                )}
            </Card>

            <form onSubmit={runSearch} className="mb-4 flex flex-wrap items-center gap-2">
                <input
                    type="search"
                    value={q}
                    onChange={(e) => setQ(e.target.value)}
                    placeholder="Search name, email, agency, message, campaign…"
                    className="w-full max-w-md rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-3.5 py-2 text-sm text-ink placeholder:text-[color:var(--color-faint)] focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40"
                />
                <Button type="submit" size="sm" variant="secondary">Search</Button>
                {search && (
                    <button type="button" onClick={clearSearch} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                        Clear
                    </button>
                )}
            </form>

            <Card className="overflow-x-auto">
                <table className="w-full min-w-[56rem] text-left text-sm">
                    <thead>
                        <tr className="border-b border-[color:var(--color-border)] text-xs uppercase tracking-wider text-[color:var(--color-faint)]">
                            <th className="px-5 py-3 font-medium">Received</th>
                            <th className="px-5 py-3 font-medium">From</th>
                            <th className="px-5 py-3 font-medium">Message</th>
                            <th className="px-5 py-3 font-medium">Channel</th>
                            <th className="px-5 py-3 font-medium">Source</th>
                            <th className="px-5 py-3 font-medium text-right"><span className="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        {enquiries.length === 0 && (
                            <tr>
                                <td colSpan={6} className="px-5 py-10 text-center text-[color:var(--color-muted)]">
                                    {search ? 'No enquiries match that search.' : 'No enquiries yet.'}
                                </td>
                            </tr>
                        )}
                        {enquiries.map((e) => (
                            <tr key={e.id} className="border-b border-[color:var(--color-border-soft)] last:border-0 align-top">
                                <td className="whitespace-nowrap px-5 py-4 text-[color:var(--color-muted)]">
                                    {short(e.received_at)}
                                    {! e.emailed && (
                                        <span className="mt-1 block text-xs text-[#fb7185]">Email failed</span>
                                    )}
                                </td>
                                <td className="px-5 py-4">
                                    <span className="block font-medium text-ink">{e.name}</span>
                                    <span className="block text-xs text-[color:var(--color-muted)]">{e.email}</span>
                                    {e.agency && <span className="block text-xs text-[color:var(--color-faint)]">{e.agency}</span>}
                                </td>
                                <td className="max-w-xs px-5 py-4 text-[color:var(--color-muted)]">
                                    {e.topic && <Badge>{e.topic}</Badge>}
                                    <span className={`block ${e.topic ? 'mt-1.5' : ''}`}>{e.excerpt}</span>
                                </td>
                                <td className="whitespace-nowrap px-5 py-4">
                                    <Badge tone={e.channel === 'Direct' ? 'muted' : 'brand'}>{e.channel}</Badge>
                                </td>
                                <td className="max-w-[14rem] px-5 py-4 text-xs text-[color:var(--color-muted)]">
                                    {e.utm_source || e.utm_medium || e.utm_campaign ? (
                                        <>
                                            <span className="block text-ink">{[e.utm_source, e.utm_medium].filter(Boolean).join(' / ')}</span>
                                            {e.utm_campaign && <span className="block">{e.utm_campaign}</span>}
                                        </>
                                    ) : (
                                        <span className="block truncate" title={e.landing_page ?? ''}>{e.landing_page ? e.landing_page.replace(/^https?:\/\/[^/]+/, '') || '/' : '—'}</span>
                                    )}
                                </td>
                                <td className="px-5 py-4 text-right">
                                    <Link href={route('admin.enquiries.show', e.id)} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                                        View
                                    </Link>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </Card>

            {lastPage > 1 && (
                <div className="mt-4 flex items-center justify-between text-sm">
                    <span className="text-[color:var(--color-faint)]">Page {currentPage} of {lastPage}</span>
                    <div className="flex gap-4">
                        {currentPage > 1 && (
                            <Link href={route('admin.enquiries.index', query(currentPage - 1))} className="text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                                &larr; Previous
                            </Link>
                        )}
                        {currentPage < lastPage && (
                            <Link href={route('admin.enquiries.index', query(currentPage + 1))} className="text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                                Next &rarr;
                            </Link>
                        )}
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
