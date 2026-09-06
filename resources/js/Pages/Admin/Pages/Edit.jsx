import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import ImagePicker from '../../../Components/ImagePicker';
import { Button, Card, Checkbox, Field, Select, Textarea, TextInput } from '../../../Components/UI';

export default function PageEdit({ page, redirects }) {
    const { data, setData, put, processing, errors } = useForm({
        name: page.name,
        slug: page.slug,
        is_active: page.is_active,
        meta_title: page.meta_title ?? '',
        meta_description: page.meta_description ?? '',
        meta_keywords: page.meta_keywords ?? '',
        canonical_url: page.canonical_url ?? '',
        robots_index: page.robots_index,
        robots_follow: page.robots_follow,
        og_title: page.og_title ?? '',
        og_description: page.og_description ?? '',
        og_image: page.og_image ?? '',
        og_type: page.og_type ?? 'website',
        twitter_card: page.twitter_card ?? 'summary_large_image',
        twitter_title: page.twitter_title ?? '',
        twitter_description: page.twitter_description ?? '',
        twitter_image: page.twitter_image ?? '',
        json_ld: page.json_ld ?? '',
        head_scripts: page.head_scripts ?? '',
        sitemap_priority: page.sitemap_priority,
        sitemap_frequency: page.sitemap_frequency,
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('admin.pages.update', page.id));
    };

    return (
        <AdminLayout
            title={page.name}
            heading={
                <div>
                    <h1 className="text-2xl font-semibold text-ink">{page.name}</h1>
                    <p className="mt-1 font-mono text-xs text-[color:var(--color-faint)]">key: {page.key}</p>
                </div>
            }
        >
            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div className="space-y-6">
                    <Card className="space-y-4 p-6">
                        <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Basics</h2>

                        <Field label="Name" htmlFor="name" error={errors.name}>
                            <TextInput id="name" value={data.name} error={errors.name} onChange={(e) => setData('name', e.target.value)} />
                        </Field>

                        <Field label="Slug" htmlFor="slug" error={errors.slug} hint="Changing this leaves a redirect behind at the old address.">
                            <TextInput id="slug" value={data.slug} error={errors.slug} onChange={(e) => setData('slug', e.target.value)} />
                        </Field>

                        <label className="flex items-center gap-2.5 text-sm text-[color:var(--color-muted)]">
                            <Checkbox checked={data.is_active} onChange={(e) => setData('is_active', e.target.checked)} />
                            Active
                        </label>
                    </Card>

                    <Card className="space-y-4 p-6">
                        <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Search metadata</h2>

                        <Field label="Meta title" htmlFor="meta_title" error={errors.meta_title}>
                            <TextInput id="meta_title" value={data.meta_title} error={errors.meta_title} onChange={(e) => setData('meta_title', e.target.value)} />
                        </Field>

                        <Field label="Meta description" htmlFor="meta_description" error={errors.meta_description}>
                            <Textarea id="meta_description" rows={3} value={data.meta_description} error={errors.meta_description} onChange={(e) => setData('meta_description', e.target.value)} />
                        </Field>

                        <Field label="Meta keywords" htmlFor="meta_keywords" error={errors.meta_keywords}>
                            <TextInput id="meta_keywords" value={data.meta_keywords} error={errors.meta_keywords} onChange={(e) => setData('meta_keywords', e.target.value)} />
                        </Field>

                        <Field label="Canonical URL" htmlFor="canonical_url" error={errors.canonical_url} hint="Leave blank to use the page's own address.">
                            <TextInput id="canonical_url" value={data.canonical_url} error={errors.canonical_url} onChange={(e) => setData('canonical_url', e.target.value)} />
                        </Field>

                        <div className="flex flex-wrap gap-6">
                            <label className="flex items-center gap-2.5 text-sm text-[color:var(--color-muted)]">
                                <Checkbox checked={data.robots_index} onChange={(e) => setData('robots_index', e.target.checked)} />
                                Indexable
                            </label>
                            <label className="flex items-center gap-2.5 text-sm text-[color:var(--color-muted)]">
                                <Checkbox checked={data.robots_follow} onChange={(e) => setData('robots_follow', e.target.checked)} />
                                Follow links
                            </label>
                        </div>
                    </Card>

                    <Card className="space-y-4 p-6">
                        <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Social sharing</h2>

                        <Field label="OG title" htmlFor="og_title" error={errors.og_title}>
                            <TextInput id="og_title" value={data.og_title} error={errors.og_title} onChange={(e) => setData('og_title', e.target.value)} />
                        </Field>
                        <Field label="OG description" htmlFor="og_description" error={errors.og_description}>
                            <Textarea id="og_description" rows={2} value={data.og_description} error={errors.og_description} onChange={(e) => setData('og_description', e.target.value)} />
                        </Field>
                        <ImagePicker label="OG image" value={data.og_image} onChange={(url) => setData('og_image', url)} error={errors.og_image} />
                        <Field label="OG type" htmlFor="og_type" error={errors.og_type}>
                            <TextInput id="og_type" value={data.og_type} error={errors.og_type} onChange={(e) => setData('og_type', e.target.value)} />
                        </Field>

                        <div className="border-t border-[color:var(--color-border-soft)] pt-4">
                            <Field label="Twitter card" htmlFor="twitter_card" error={errors.twitter_card}>
                                <Select id="twitter_card" value={data.twitter_card} error={errors.twitter_card} onChange={(e) => setData('twitter_card', e.target.value)}>
                                    <option value="summary">summary</option>
                                    <option value="summary_large_image">summary_large_image</option>
                                </Select>
                            </Field>
                        </div>
                        <Field label="Twitter title" htmlFor="twitter_title" error={errors.twitter_title}>
                            <TextInput id="twitter_title" value={data.twitter_title} error={errors.twitter_title} onChange={(e) => setData('twitter_title', e.target.value)} />
                        </Field>
                        <Field label="Twitter description" htmlFor="twitter_description" error={errors.twitter_description}>
                            <Textarea id="twitter_description" rows={2} value={data.twitter_description} error={errors.twitter_description} onChange={(e) => setData('twitter_description', e.target.value)} />
                        </Field>
                        <ImagePicker label="Twitter image" value={data.twitter_image} onChange={(url) => setData('twitter_image', url)} error={errors.twitter_image} />
                    </Card>

                    <Card className="space-y-4 p-6">
                        <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Advanced</h2>

                        <Field label="JSON-LD" htmlFor="json_ld" error={errors.json_ld} hint="Raw JSON-LD, without the surrounding <script> tag.">
                            <Textarea id="json_ld" rows={5} className="font-mono text-xs" value={data.json_ld} error={errors.json_ld} onChange={(e) => setData('json_ld', e.target.value)} />
                        </Field>
                        <Field label="Head scripts" htmlFor="head_scripts" error={errors.head_scripts} hint="Raw HTML/JS injected verbatim before </head> on this page only.">
                            <Textarea id="head_scripts" rows={5} className="font-mono text-xs" value={data.head_scripts} error={errors.head_scripts} onChange={(e) => setData('head_scripts', e.target.value)} />
                        </Field>
                    </Card>
                </div>

                <div className="space-y-6">
                    <Card className="space-y-4 p-6">
                        <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Sitemap</h2>

                        <Field label="Priority" htmlFor="sitemap_priority" error={errors.sitemap_priority}>
                            <TextInput id="sitemap_priority" value={data.sitemap_priority} error={errors.sitemap_priority} onChange={(e) => setData('sitemap_priority', e.target.value)} />
                        </Field>

                        <Field label="Frequency" htmlFor="sitemap_frequency" error={errors.sitemap_frequency}>
                            <Select id="sitemap_frequency" value={data.sitemap_frequency} error={errors.sitemap_frequency} onChange={(e) => setData('sitemap_frequency', e.target.value)}>
                                {['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'].map((f) => (
                                    <option key={f} value={f}>{f}</option>
                                ))}
                            </Select>
                        </Field>
                    </Card>

                    {redirects.length > 0 && (
                        <Card className="p-6">
                            <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Old addresses</h2>
                            <ul className="mt-3 space-y-2">
                                {redirects.map((r) => (
                                    <li key={r.id} className="flex items-center justify-between font-mono text-xs text-[color:var(--color-muted)]">
                                        <span>/{r.old_slug}</span>
                                        <span className="text-[color:var(--color-faint)]">{r.status_code} →</span>
                                    </li>
                                ))}
                            </ul>
                        </Card>
                    )}

                    <Button type="submit" size="lg" className="w-full" disabled={processing}>
                        Save page
                    </Button>
                </div>
            </form>
        </AdminLayout>
    );
}
