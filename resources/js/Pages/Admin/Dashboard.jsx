import { Link } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import { Card, Badge } from '../../Components/UI';

export default function Dashboard({ pageCount, userCount, enquiryCount, enquiriesLast30Days, integrations }) {
    return (
        <AdminLayout
            title="Dashboard"
            heading={
                <div>
                    <h1 className="text-2xl font-semibold text-ink">Dashboard</h1>
                    <p className="mt-1 text-sm text-[color:var(--color-muted)]">An overview of the site's content and marketing setup.</p>
                </div>
            }
        >
            <div className="grid gap-4 sm:grid-cols-3">
                <Card className="p-6">
                    <p className="text-xs uppercase tracking-wider text-[color:var(--color-faint)]">Pages</p>
                    <p className="mt-2 text-3xl font-semibold text-ink">{pageCount}</p>
                    <p className="mt-1 text-sm text-[color:var(--color-muted)]">Managed via the Pages screen.</p>
                </Card>

                <Card className="p-6">
                    <p className="text-xs uppercase tracking-wider text-[color:var(--color-faint)]">Admin users</p>
                    <p className="mt-2 text-3xl font-semibold text-ink">{userCount}</p>
                    <p className="mt-1 text-sm text-[color:var(--color-muted)]">Managed via the Users screen.</p>
                </Card>

                <Card className="p-6">
                    <p className="text-xs uppercase tracking-wider text-[color:var(--color-faint)]">Enquiries</p>
                    <p className="mt-2 text-3xl font-semibold text-ink">{enquiryCount}</p>
                    <p className="mt-1 text-sm text-[color:var(--color-muted)]">
                        {enquiriesLast30Days} in the last 30 days. <Link href={route('admin.enquiries.index')} className="text-ink underline-offset-2 hover:underline">See where they came from</Link>.
                    </p>
                </Card>
            </div>

            <Card className="mt-4 p-6">
                <p className="text-xs uppercase tracking-wider text-[color:var(--color-faint)]">Integrations</p>
                <ul className="mt-3 divide-y divide-[color:var(--color-border-soft)]">
                    {integrations.map((i) => (
                        <li key={i.key} className="flex items-center justify-between py-3 text-sm">
                            <span className="text-ink">{i.label}</span>
                            <Badge tone={i.configured ? 'brand' : 'muted'}>{i.configured ? 'Configured' : 'Not configured'}</Badge>
                        </li>
                    ))}
                </ul>
            </Card>
        </AdminLayout>
    );
}
