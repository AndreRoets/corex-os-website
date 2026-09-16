import { Link } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Badge, Button, Card } from '../../../Components/UI';
import { short } from '../../../sast';

function Row({ label, children, mono = false }) {
    return (
        <div className="grid gap-1 py-3 sm:grid-cols-[11rem_1fr] sm:gap-4">
            <dt className="text-xs uppercase tracking-wider text-[color:var(--color-faint)]">{label}</dt>
            <dd className={`min-w-0 break-words text-sm text-ink ${mono ? 'font-mono text-xs' : ''}`}>{children ?? <span className="text-[color:var(--color-faint)]">—</span>}</dd>
        </div>
    );
}

export default function EnquiryShow({ enquiry: e }) {
    return (
        <AdminLayout
            title={e.name}
            heading={
                <div>
                    <Link href={route('admin.enquiries.index')} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                        &larr; All enquiries
                    </Link>

                    <div className="mt-2 flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <h1 className="text-2xl font-semibold text-ink">{e.name}</h1>
                            <p className="mt-1 text-sm text-[color:var(--color-muted)]">
                                {short(e.received_at)}
                                {e.agency && <> · {e.agency}</>}
                                {e.topic && <> · {e.topic}</>}
                            </p>
                        </div>
                        <Button as="a" href={`mailto:${e.email}?subject=${encodeURIComponent('Re: your CoreX OS enquiry')}`}>
                            Reply by email
                        </Button>
                    </div>
                </div>
            }
        >
            <div className="grid gap-4 lg:grid-cols-[1.2fr_1fr]">
                <Card className="p-6">
                    <h2 className="text-sm font-semibold text-ink">Message</h2>
                    <p className="mt-3 whitespace-pre-wrap text-sm leading-relaxed text-ink">{e.message}</p>

                    <dl className="mt-6 divide-y divide-[color:var(--color-border-soft)] border-t border-[color:var(--color-border-soft)]">
                        <Row label="Email"><a href={`mailto:${e.email}`} className="text-[color:var(--color-brand-400)] hover:underline">{e.email}</a></Row>
                        <Row label="Phone">{e.phone}</Row>
                        <Row label="Agency">{e.agency}</Row>
                        <Row label="About">{e.topic}</Row>
                        <Row label="Notification">
                            {e.emailed_at
                                ? <>Emailed to the sales inbox {short(e.emailed_at)}</>
                                : <span className="text-[#fb7185]">The notification email failed — this row is the only copy.</span>}
                        </Row>
                    </dl>
                </Card>

                <Card className="p-6">
                    <div className="flex items-center justify-between">
                        <h2 className="text-sm font-semibold text-ink">Where they came from</h2>
                        <Badge tone={e.channel === 'Direct' ? 'muted' : 'brand'}>{e.channel}</Badge>
                    </div>

                    <dl className="mt-3 divide-y divide-[color:var(--color-border-soft)]">
                        <Row label="Source">{e.utm_source}</Row>
                        <Row label="Medium">{e.utm_medium}</Row>
                        <Row label="Campaign">{e.utm_campaign}</Row>
                        <Row label="Term">{e.utm_term}</Row>
                        <Row label="Content">{e.utm_content}</Row>
                        <Row label="Google click ID" mono>{e.gclid}</Row>
                        <Row label="Facebook click ID" mono>{e.fbclid}</Row>
                        <Row label="Landing page" mono>{e.landing_page}</Row>
                        <Row label="Referrer" mono>{e.referrer}</Row>
                        <Row label="Sent from" mono>{e.page_url}</Row>
                        <Row label="First visit">{e.first_seen_at ? short(e.first_seen_at) : null}</Row>
                        <Row label="IP address" mono>{e.ip_address}</Row>
                        <Row label="Browser" mono>{e.user_agent}</Row>
                    </dl>
                </Card>
            </div>
        </AdminLayout>
    );
}
