import { useMemo } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Button } from '../../../Components/UI';
import { dateForInput, endTimeForInput, timeChoices, timeForInput } from '../../../sast';

export default function WebinarForm({ webinar, slug, registrationCount, unknownFields, contactEmail }) {
    const isEdit = webinar !== null;

    const { data, setData, post, put, processing, errors } = useForm({
        title: webinar?.title ?? '',
        slug: webinar?.slug ?? '',
        description: webinar?.description ?? '',
        date: dateForInput(webinar?.starts_at),
        start_time: timeForInput(webinar?.starts_at),
        end_time: endTimeForInput(webinar?.starts_at, webinar?.duration_minutes ?? 60),
        closes_date: dateForInput(webinar?.registration_closes_at),
        closes_time: timeForInput(webinar?.registration_closes_at) || '17:00',
        access_ends_days_after: webinar?.access_ends_days_after ?? 3,
        reminder_hours_before: webinar?.reminder_hours_before ?? 24,
        _unknown: unknownFields ?? [],
    });

    const startChoices = useMemo(() => timeChoices(data.start_time), [data.start_time]);
    const endChoices = useMemo(() => timeChoices(data.end_time), [data.end_time]);
    const closesChoices = useMemo(() => timeChoices(data.closes_time), [data.closes_time]);

    const lengthMinutes = useMemo(() => {
        if (! data.start_time || ! data.end_time) return null;
        const [sh, sm] = data.start_time.split(':').map(Number);
        const [eh, em] = data.end_time.split(':').map(Number);
        return eh * 60 + em - (sh * 60 + sm);
    }, [data.start_time, data.end_time]);

    const lengthLabel = () => {
        const n = lengthMinutes ?? 0;
        const h = Math.floor(n / 60);
        const m = n % 60;
        const parts = [];
        if (h) parts.push(`${h} hour${h === 1 ? '' : 's'}`);
        if (m) parts.push(`${m} minutes`);
        return parts.join(' ') || '0 minutes';
    };

    const onStartChange = (value) => {
        setData((prev) => {
            const next = { ...prev, start_time: value };

            // Only ever fill a blank or now-impossible finish time — never
            // overwrite one that was set deliberately.
            const [sh, sm] = value.split(':').map(Number);
            const [eh, em] = (prev.end_time || '').split(':').map((n) => Number(n) || 0);
            const currentLength = prev.end_time ? eh * 60 + em - (sh * 60 + sm) : -1;

            if (! prev.end_time || currentLength <= 0) {
                const end = Math.min(sh * 60 + sm + 60, 23 * 60 + 45);
                next.end_time = `${String(Math.floor(end / 60)).padStart(2, '0')}:${String(end % 60).padStart(2, '0')}`;
            }

            return next;
        });
    };

    const submit = (e) => {
        e.preventDefault();
        if (isEdit) {
            put(route('admin.webinars.update', slug));
        } else {
            post(route('admin.webinars.store'));
        }
    };

    return (
        <AdminLayout
            title={isEdit ? 'Edit webinar' : 'New webinar'}
            heading={
                <div>
                    <Link href={route('admin.webinars.index')} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                        &larr; All webinars
                    </Link>
                    <h1 className="mt-2 text-2xl font-semibold text-ink">{isEdit ? 'Edit webinar' : 'New webinar'}</h1>
                </div>
            }
        >
            <div className="max-w-2xl">
                {isEdit && registrationCount > 0 && (
                    <div className="mb-6 rounded-md border border-[#f59e0b]/40 bg-[#f59e0b]/10 px-4 py-3.5 text-sm leading-relaxed text-ink" role="status">
                        <p>
                            People have already registered. Changing the date or the access window applies to anyone who signs up
                            from now on — those already registered keep the end date they were given in their email.
                        </p>
                        <p className="mt-1.5 text-xs text-[color:var(--color-muted)]">
                            {registrationCount} registration{registrationCount === 1 ? '' : 's'} so far.
                        </p>
                    </div>
                )}

                {isEdit && unknownFields?.length > 0 && (
                    <div className="mb-6 rounded-md border border-[color:var(--color-border)] bg-[color:var(--color-surface-2)] px-4 py-3.5 text-sm leading-relaxed text-[color:var(--color-muted)]" role="status">
                        CoreX did not send back {unknownFields.map((f) => f.replace(/_/g, ' ')).join(' or ')}, so those are shown
                        blank below and will be <strong className="text-ink">left exactly as they are</strong> when you save.
                        Nothing is cleared by saving this form.
                    </div>
                )}

                <form onSubmit={submit} className="card space-y-6 p-6 sm:p-8">
                    <div>
                        <label htmlFor="title" className="block text-sm font-medium text-ink">Title</label>
                        <p className="mt-1 text-xs text-[color:var(--color-muted)]">Registrants see this in their confirmation email and calendar.</p>
                        <input
                            id="title" type="text" required value={data.title}
                            onChange={(e) => setData('title', e.target.value)}
                            placeholder="CoreX OS — a walkthrough for agency principals"
                            className={`mt-2 w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.title ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                        />
                        {errors.title && <p className="mt-1.5 text-xs text-[#fb7185]">{errors.title}</p>}
                    </div>

                    <div>
                        <label htmlFor="slug" className="block text-sm font-medium text-ink">Link name</label>
                        <p className="mt-1 text-xs text-[color:var(--color-muted)]">The short name in the registration web address. Leave blank to build it from the title.</p>
                        <input
                            id="slug" type="text" value={data.slug}
                            onChange={(e) => setData('slug', e.target.value)}
                            placeholder="corex-walkthrough-sept"
                            className={`mt-2 w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 font-mono text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.slug ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                        />
                        {errors.slug && <p className="mt-1.5 text-xs text-[#fb7185]">{errors.slug}</p>}
                    </div>

                    <div>
                        <label htmlFor="description" className="block text-sm font-medium text-ink">What you&rsquo;ll cover</label>
                        <p className="mt-1 text-xs text-[color:var(--color-muted)]">Shown on the registration page and in the confirmation email.</p>
                        <textarea
                            id="description" rows={4} value={data.description}
                            onChange={(e) => setData('description', e.target.value)}
                            placeholder="Everything a principal needs to see, in 45 minutes."
                            className={`mt-2 w-full resize-y rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.description ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                        />
                        {errors.description && <p className="mt-1.5 text-xs text-[#fb7185]">{errors.description}</p>}
                    </div>

                    {/* One date, then a start and a finish time. A webinar happens on a
                        single day, so asking for the date twice only creates ways to get
                        it wrong — an end on the wrong day, or "12:00 AM" (midnight) read
                        as earlier than a 10:00 AM start. */}
                    <div>
                        <label htmlFor="date" className="block text-sm font-medium text-ink">Date and time</label>
                        <p className="mt-1 text-xs text-[color:var(--color-muted)]">
                            South African time. Registration closes automatically when the webinar starts, and the finishing
                            time is what shows in the calendar invite.
                        </p>

                        <div className="mt-2 grid gap-4 sm:grid-cols-[1.4fr_1fr_1fr]">
                            <div>
                                <input
                                    id="date" type="date" required value={data.date}
                                    onChange={(e) => setData('date', e.target.value)}
                                    className={`w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.date ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                                />
                                <span className="mt-1 block text-xs text-[color:var(--color-faint)]">Date</span>
                                {errors.date && <p className="mt-1 text-xs text-[#fb7185]">{errors.date}</p>}
                            </div>

                            <div>
                                <label htmlFor="start_time" className="sr-only">Starting time</label>
                                <select
                                    id="start_time" required value={data.start_time}
                                    onChange={(e) => onStartChange(e.target.value)}
                                    className={`w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.start_time ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                                >
                                    {startChoices.map(([value, label]) => (
                                        <option key={value} value={value}>{label}</option>
                                    ))}
                                </select>
                                <span className="mt-1 block text-xs text-[color:var(--color-faint)]">Starts</span>
                                {errors.start_time && <p className="mt-1 text-xs text-[#fb7185]">{errors.start_time}</p>}
                            </div>

                            <div>
                                <label htmlFor="end_time" className="sr-only">Finishing time</label>
                                <select
                                    id="end_time" required value={data.end_time}
                                    onChange={(e) => setData('end_time', e.target.value)}
                                    className={`w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.end_time ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                                >
                                    {endChoices.map(([value, label]) => (
                                        <option key={value} value={value}>{label}</option>
                                    ))}
                                </select>
                                <span className="mt-1 block text-xs text-[color:var(--color-faint)]">Ends</span>
                                {errors.end_time && <p className="mt-1 text-xs text-[#fb7185]">{errors.end_time}</p>}
                            </div>
                        </div>

                        {data.start_time && data.end_time && (
                            <p className={`mt-2 text-xs ${lengthMinutes > 0 ? 'text-[color:var(--color-muted)]' : 'text-[#fb7185]'}`}>
                                {lengthMinutes > 0 ? (
                                    `Runs for ${lengthLabel()}.`
                                ) : (
                                    <>That finishing time is not after the starting time. Midday is <strong>12:00 noon</strong> — 12:00 midnight is the very start of the day.</>
                                )}
                            </p>
                        )}
                    </div>

                    {/* Optional deadline, earlier than the webinar. Blank keeps
                        registration open right up to the start. */}
                    <div>
                        <label htmlFor="closes_date" className="block text-sm font-medium text-ink">
                            Registration closes <span className="font-normal text-[color:var(--color-faint)]">(optional)</span>
                        </label>
                        <p className="mt-1 text-xs text-[color:var(--color-muted)]">
                            Set a cut-off if people must sign up by a certain point — useful when the list has to be final
                            before the day. Leave blank and registration stays open until the webinar starts. After the
                            cut-off, the page invites them to email <span className="font-mono">{contactEmail}</span> instead.
                        </p>

                        <div className="mt-2 grid gap-4 sm:grid-cols-[1.4fr_1fr_1fr]">
                            <div>
                                <input
                                    id="closes_date" type="date" value={data.closes_date}
                                    onChange={(e) => setData('closes_date', e.target.value)}
                                    className={`w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.closes_date ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                                />
                                <span className="mt-1 block text-xs text-[color:var(--color-faint)]">Date</span>
                                {errors.closes_date && <p className="mt-1 text-xs text-[#fb7185]">{errors.closes_date}</p>}
                            </div>

                            <div>
                                <label htmlFor="closes_time" className="sr-only">Time registration closes</label>
                                <select
                                    id="closes_time" value={data.closes_time}
                                    onChange={(e) => setData('closes_time', e.target.value)}
                                    className={`w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.closes_time ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                                >
                                    {closesChoices.map(([value, label]) => (
                                        <option key={value} value={value}>{label}</option>
                                    ))}
                                </select>
                                <span className="mt-1 block text-xs text-[color:var(--color-faint)]">Time</span>
                                {errors.closes_time && <p className="mt-1 text-xs text-[#fb7185]">{errors.closes_time}</p>}
                            </div>

                            <div />
                        </div>
                    </div>

                    <div className="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label htmlFor="access_ends_days_after" className="block text-sm font-medium text-ink">Demo access ends this many days after</label>
                            <p className="mt-1 text-xs text-[color:var(--color-muted)]">
                                Everyone who registers loses their demo login at the end of that day — whether or not they used
                                it. Enter 0 to end it on the day of the webinar.
                            </p>
                            <input
                                id="access_ends_days_after" type="number" min="0" step="1" value={data.access_ends_days_after}
                                onChange={(e) => setData('access_ends_days_after', e.target.value)}
                                className={`mt-2 w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.access_ends_days_after ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                            />
                            {errors.access_ends_days_after && <p className="mt-1.5 text-xs text-[#fb7185]">{errors.access_ends_days_after}</p>}
                        </div>

                        <div>
                            <label htmlFor="reminder_hours_before" className="block text-sm font-medium text-ink">Send the reminder this many hours before</label>
                            <p className="mt-1 text-xs text-[color:var(--color-muted)]">One reminder per person, with the joining link.</p>
                            <input
                                id="reminder_hours_before" type="number" min="0" step="1" value={data.reminder_hours_before}
                                onChange={(e) => setData('reminder_hours_before', e.target.value)}
                                className={`mt-2 w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)] ${errors.reminder_hours_before ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'}`}
                            />
                            {errors.reminder_hours_before && <p className="mt-1.5 text-xs text-[#fb7185]">{errors.reminder_hours_before}</p>}
                        </div>
                    </div>

                    <div className="flex flex-wrap items-center gap-3 border-t border-[color:var(--color-border)] pt-6">
                        <Button type="submit" disabled={processing}>{isEdit ? 'Save changes' : 'Create webinar'}</Button>
                        <Button as="a" href={route('admin.webinars.index')} variant="ghost">Cancel</Button>
                    </div>
                </form>
            </div>
        </AdminLayout>
    );
}
