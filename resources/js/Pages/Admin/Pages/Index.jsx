import { Link } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Card, Badge } from '../../../Components/UI';

export default function PagesIndex({ pages }) {
    return (
        <AdminLayout
            title="Pages"
            heading={
                <div>
                    <h1 className="text-2xl font-semibold text-ink">Pages</h1>
                    <p className="mt-1 text-sm text-[color:var(--color-muted)]">
                        SEO metadata for every static route on the site. Slugs are seeded, not created here.
                    </p>
                </div>
            }
        >
            <Card className="overflow-x-auto">
                <table className="w-full min-w-[40rem] text-left text-sm">
                    <thead>
                        <tr className="border-b border-[color:var(--color-border)] text-xs uppercase tracking-wider text-[color:var(--color-faint)]">
                            <th className="px-5 py-3 font-medium">Page</th>
                            <th className="px-5 py-3 font-medium">Slug</th>
                            <th className="px-5 py-3 font-medium">Status</th>
                            <th className="px-5 py-3 font-medium">Robots</th>
                            <th className="px-5 py-3 font-medium text-right">
                                <span className="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {pages.map((page) => (
                            <tr key={page.id} className="border-b border-[color:var(--color-border-soft)] last:border-0">
                                <td className="px-5 py-4">
                                    <span className="font-medium text-ink">{page.name}</span>
                                    <span className="mt-0.5 block font-mono text-xs text-[color:var(--color-faint)]">{page.key}</span>
                                </td>
                                <td className="px-5 py-4 font-mono text-xs text-[color:var(--color-muted)]">/{page.slug === 'home' ? '' : page.slug}</td>
                                <td className="px-5 py-4">
                                    <Badge tone={page.is_active ? 'brand' : 'muted'}>{page.is_active ? 'Active' : 'Inactive'}</Badge>
                                </td>
                                <td className="px-5 py-4">
                                    <Badge tone={page.robots_index ? 'default' : 'muted'}>
                                        {page.robots_index ? 'index' : 'noindex'}, {page.robots_follow ? 'follow' : 'nofollow'}
                                    </Badge>
                                </td>
                                <td className="px-5 py-4 text-right">
                                    <Link href={route('admin.pages.edit', page.id)} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                                        Edit
                                    </Link>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </Card>
        </AdminLayout>
    );
}
