import { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Alert, Badge, Button, Card } from '../../../Components/UI';
import { longDate, timeForInput } from '../../../sast';

function CopyLink({ url }) {
    const [copied, setCopied] = useState(false);

    const copy = () => {
        navigator.clipboard.writeText(url).then(() => {
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        });
    };

    return (
        <div className="flex items-center gap-2">
            <input
                type="text"
                readOnly
                value={url}
                onFocus={(e) => e.target.select()}
                className="w-52 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-bg-soft)] px-2.5 py-1.5 font-mono text-xs text-[color:var(--color-muted)]"
            />
            <button
                type="button"
                onClick={copy}
                className="shrink-0 rounded-md border border-[color:var(--color-border)] px-2.5 py-1.5 text-xs text-[color:var(--color-muted)] transition duration-300 hover:border-[color:var(--color-brand)] hover:text-ink"
            >
                {copied ? <span className="text-[color:var(--color-brand-400)]">Copied</span> : 'Copy'}
            </button>
        </div>
    );
}

export default function WebinarsIndex({ webinars, includeArchived, problem }) {
    const archive = (slug) => {
        if (! confirm('Archive this webinar? The registration link stops working immediately — nobody else can sign up or be given demo access. Everyone who already registered keeps theirs.')) {
            return;
        }
        router.delete(route('admin.webinars.archive', slug));
    };

    return (
        <AdminLayout
            title="Webinars"
            heading={
                <div className="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-semibold text-ink">Webinars</h1>
                        <p className="mt-1 text-sm text-[color:var(--color-muted)]">
                            Create a webinar, hand out its registration link, and see who has signed up.
                        </p>
                    </div>
                    <Button as="a" href={route('admin.webinars.create')}>New webinar</Button>
                </div>
            }
        >
            <Alert tone="warning">{problem}</Alert>

            <div className="mb-4 flex items-center justify-between gap-4">
                <p className="text-sm text-[color:var(--color-muted)]">
                    {webinars.length} webinar{webinars.length === 1 ? '' : 's'}
                </p>
                <Link
                    href={route('admin.webinars.index', includeArchived ? {} : { archived: 1 })}
                    className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink"
                >
                    {includeArchived ? 'Hide archived' : 'Show archived'}
                </Link>
            </div>

            {webinars.length === 0 ? (
                <Card className="p-10 text-center">
                    <p className="text-sm text-[color:var(--color-muted)]">No webinars yet. Create one and the registration link appears here.</p>
                </Card>
            ) : (
                <Card className="overflow-x-auto">
                    <table className="w-full min-w-[52rem] text-left text-sm">
                        <thead>
                            <tr className="border-b border-[color:var(--color-border)] text-xs uppercase tracking-wider text-[color:var(--color-faint)]">
                                <th className="px-5 py-3 font-medium">Webinar</th>
                                <th className="px-5 py-3 font-medium">When</th>
                                <th className="px-5 py-3 font-medium">Status</th>
                                <th className="px-5 py-3 font-medium">Registrants</th>
                                <th className="px-5 py-3 font-medium">Registration link</th>
                                <th className="px-5 py-3 font-medium text-right">
                                    <span className="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {webinars.map((webinar) => {
                                const slug = webinar.slug ?? '';
                                const archived = !! webinar.archived;
                                const open = !! webinar.registration_open;
                                const count = Number(webinar.registration_count ?? 0);
                                const tone = archived ? 'muted' : open ? 'brand' : 'default';

                                return (
                                    <tr key={slug} className={`border-b border-[color:var(--color-border-soft)] last:border-0 ${archived ? 'opacity-60' : ''}`}>
                                        <td className="px-5 py-4">
                                            <span className="font-medium text-ink">{webinar.title ?? slug}</span>
                                            <span className="mt-0.5 block font-mono text-xs text-[color:var(--color-faint)]">{slug}</span>
                                        </td>

                                        <td className="px-5 py-4 whitespace-nowrap text-[color:var(--color-muted)]">
                                            {webinar.starts_at ? (
                                                <>
                                                    {longDate(webinar.starts_at)}
                                                    <span className="block text-xs text-[color:var(--color-faint)]">
                                                        {timeForInput(webinar.starts_at)} SAST
                                                    </span>
                                                </>
                                            ) : (
                                                '—'
                                            )}
                                        </td>

                                        <td className="px-5 py-4">
                                            <Badge tone={tone}>
                                                {webinar.status_label ?? (archived ? 'Archived' : open ? 'Open for registration' : 'Closed')}
                                            </Badge>
                                        </td>

                                        <td className="px-5 py-4">
                                            <Link
                                                href={route('admin.webinars.registrations', slug)}
                                                className="inline-flex items-center gap-2 whitespace-nowrap rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-3 py-1.5 text-sm text-ink transition duration-300 hover:border-[color:var(--color-brand)] hover:-translate-y-0.5"
                                            >
                                                View registrants
                                                <span className="rounded-full bg-[color:var(--color-brand)]/15 px-2 py-0.5 text-xs font-medium text-[color:var(--color-brand-400)]">{count}</span>
                                            </Link>
                                        </td>

                                        <td className="px-5 py-4">
                                            {webinar.registration_url ? <CopyLink url={webinar.registration_url} /> : <span className="text-xs text-[color:var(--color-faint)]">—</span>}
                                        </td>

                                        <td className="px-5 py-4">
                                            <div className="flex items-center justify-end gap-3 whitespace-nowrap">
                                                <Link href={route('admin.webinars.edit', slug)} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                                                    Edit
                                                </Link>
                                                {! archived && (
                                                    <button onClick={() => archive(slug)} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-[#fb7185]">
                                                        Archive
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                </Card>
            )}
        </AdminLayout>
    );
}
