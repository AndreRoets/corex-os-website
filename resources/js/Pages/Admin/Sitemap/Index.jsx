import AdminLayout from '../../../Layouts/AdminLayout';
import { Badge, Card } from '../../../Components/UI';

export default function SitemapIndex({ sections, previewLimit, robotsReferencesSitemap, searchConsoleVerified }) {
    return (
        <AdminLayout
            title="Sitemap"
            heading={
                <div>
                    <h1 className="text-2xl font-semibold text-ink">Sitemap</h1>
                    <p className="mt-1 text-sm text-[color:var(--color-muted)]">
                        What <a href={route('sitemap.xml')} className="underline hover:text-ink">/sitemap.xml</a> currently publishes.
                    </p>
                </div>
            }
        >
            <div className="mb-6 flex flex-wrap gap-3">
                <Badge tone={robotsReferencesSitemap ? 'brand' : 'muted'}>
                    {robotsReferencesSitemap ? 'robots.txt references the sitemap' : 'robots.txt does not reference the sitemap'}
                </Badge>
                <Badge tone={searchConsoleVerified ? 'brand' : 'muted'}>
                    {searchConsoleVerified ? 'Search Console verified' : 'Search Console not verified'}
                </Badge>
            </div>

            <div className="space-y-6">
                {sections.map((section) => (
                    <Card key={section.key} className="p-6">
                        <div className="flex flex-wrap items-baseline justify-between gap-2">
                            <h2 className="text-lg font-semibold text-ink">{section.label}</h2>
                            <span className="text-sm text-[color:var(--color-muted)]">{section.url_count} URL{section.url_count === 1 ? '' : 's'}</span>
                        </div>
                        <p className="mt-1 text-sm text-[color:var(--color-muted)]">{section.description}</p>

                        {section.urls.length > 0 ? (
                            <ul className="mt-4 divide-y divide-[color:var(--color-border-soft)] font-mono text-xs text-[color:var(--color-muted)]">
                                {section.urls.map((url) => (
                                    <li key={url.loc} className="flex items-center justify-between gap-4 py-2">
                                        <span className="truncate">{url.loc}</span>
                                        <span className="shrink-0 text-[color:var(--color-faint)]">{url.frequency} · {url.priority}</span>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <p className="mt-4 text-sm text-[color:var(--color-faint)]">Nothing in this section yet.</p>
                        )}

                        {section.url_count > previewLimit && (
                            <p className="mt-3 text-xs text-[color:var(--color-faint)]">
                                Showing the first {previewLimit} of {section.url_count}. All of them are in the live sitemap.
                            </p>
                        )}
                    </Card>
                ))}
            </div>
        </AdminLayout>
    );
}
