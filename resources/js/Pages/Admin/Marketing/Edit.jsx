import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import ImagePicker from '../../../Components/ImagePicker';
import { Button, Card, Field, Textarea, TextInput } from '../../../Components/UI';

export default function MarketingEdit({ settings }) {
    const { data, setData, put, processing, errors } = useForm({
        site_name: settings.site_name ?? '',
        default_meta_description: settings.default_meta_description ?? '',
        default_og_image: settings.default_og_image ?? '',
        default_twitter_handle: settings.default_twitter_handle ?? '',
        ga4_measurement_id: settings.ga4_measurement_id ?? '',
        gtm_container_id: settings.gtm_container_id ?? '',
        google_search_console_verification: settings.google_search_console_verification ?? '',
        google_ads_conversion_id: settings.google_ads_conversion_id ?? '',
        google_ads_conversion_label: settings.google_ads_conversion_label ?? '',
        head_scripts: settings.head_scripts ?? '',
        body_scripts: settings.body_scripts ?? '',
        robots_txt: settings.robots_txt ?? '',
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('admin.marketing.update'));
    };

    return (
        <AdminLayout
            title="Marketing settings"
            heading={
                <div>
                    <h1 className="text-2xl font-semibold text-ink">Marketing settings</h1>
                    <p className="mt-1 text-sm text-[color:var(--color-muted)]">
                        Global SEO defaults and analytics, injected site-wide — nothing here is hardcoded in a template.
                    </p>
                </div>
            }
        >
            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-2">
                <Card className="space-y-4 p-6">
                    <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Site defaults</h2>

                    <Field label="Site name" htmlFor="site_name" error={errors.site_name}>
                        <TextInput id="site_name" value={data.site_name} error={errors.site_name} onChange={(e) => setData('site_name', e.target.value)} />
                    </Field>
                    <Field label="Default meta description" htmlFor="default_meta_description" error={errors.default_meta_description}>
                        <Textarea id="default_meta_description" rows={3} value={data.default_meta_description} error={errors.default_meta_description} onChange={(e) => setData('default_meta_description', e.target.value)} />
                    </Field>
                    <ImagePicker label="Default OG image" value={data.default_og_image} onChange={(url) => setData('default_og_image', url)} error={errors.default_og_image} />
                    <Field label="Default Twitter handle" htmlFor="default_twitter_handle" error={errors.default_twitter_handle}>
                        <TextInput id="default_twitter_handle" placeholder="@corexos" value={data.default_twitter_handle} error={errors.default_twitter_handle} onChange={(e) => setData('default_twitter_handle', e.target.value)} />
                    </Field>
                </Card>

                <Card className="space-y-4 p-6">
                    <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Analytics &amp; verification</h2>

                    <Field label="GA4 measurement ID" htmlFor="ga4_measurement_id" error={errors.ga4_measurement_id} hint="e.g. G-XXXXXXXXXX">
                        <TextInput id="ga4_measurement_id" value={data.ga4_measurement_id} error={errors.ga4_measurement_id} onChange={(e) => setData('ga4_measurement_id', e.target.value)} />
                    </Field>
                    <Field label="GTM container ID" htmlFor="gtm_container_id" error={errors.gtm_container_id} hint="e.g. GTM-XXXXXXX">
                        <TextInput id="gtm_container_id" value={data.gtm_container_id} error={errors.gtm_container_id} onChange={(e) => setData('gtm_container_id', e.target.value)} />
                    </Field>
                    <Field label="Search Console verification" htmlFor="google_search_console_verification" error={errors.google_search_console_verification}>
                        <TextInput id="google_search_console_verification" value={data.google_search_console_verification} error={errors.google_search_console_verification} onChange={(e) => setData('google_search_console_verification', e.target.value)} />
                    </Field>
                    <Field label="Google Ads conversion ID" htmlFor="google_ads_conversion_id" error={errors.google_ads_conversion_id}>
                        <TextInput id="google_ads_conversion_id" value={data.google_ads_conversion_id} error={errors.google_ads_conversion_id} onChange={(e) => setData('google_ads_conversion_id', e.target.value)} />
                    </Field>
                    <Field label="Google Ads conversion label" htmlFor="google_ads_conversion_label" error={errors.google_ads_conversion_label}>
                        <TextInput id="google_ads_conversion_label" value={data.google_ads_conversion_label} error={errors.google_ads_conversion_label} onChange={(e) => setData('google_ads_conversion_label', e.target.value)} />
                    </Field>
                </Card>

                <Card className="space-y-4 p-6 lg:col-span-2">
                    <h2 className="text-sm font-semibold uppercase tracking-wider text-[color:var(--color-faint)]">Raw snippets</h2>

                    <Field label="Head scripts" htmlFor="head_scripts" error={errors.head_scripts} hint="Injected verbatim before </head> on every public page.">
                        <Textarea id="head_scripts" rows={5} className="font-mono text-xs" value={data.head_scripts} error={errors.head_scripts} onChange={(e) => setData('head_scripts', e.target.value)} />
                    </Field>
                    <Field label="Body scripts" htmlFor="body_scripts" error={errors.body_scripts} hint="Injected verbatim right after <body> on every public page.">
                        <Textarea id="body_scripts" rows={5} className="font-mono text-xs" value={data.body_scripts} error={errors.body_scripts} onChange={(e) => setData('body_scripts', e.target.value)} />
                    </Field>
                    <Field label="robots.txt" htmlFor="robots_txt" error={errors.robots_txt} hint="Served verbatim at /robots.txt. Leave blank to use the default.">
                        <Textarea id="robots_txt" rows={5} className="font-mono text-xs" value={data.robots_txt} error={errors.robots_txt} onChange={(e) => setData('robots_txt', e.target.value)} />
                    </Field>
                </Card>

                <div className="lg:col-span-2">
                    <Button type="submit" size="lg" disabled={processing}>
                        Save settings
                    </Button>
                </div>
            </form>
        </AdminLayout>
    );
}
