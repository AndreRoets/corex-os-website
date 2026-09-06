const SIZES = {
    xs: 'text-xs px-3 py-1.5',
    sm: 'text-sm px-3.5 py-2',
    md: 'text-sm px-5 py-2.5',
    lg: 'text-base px-6 py-3',
};

const VARIANTS = {
    primary:
        'bg-[color:var(--color-brand)] text-white shadow-[0_8px_30px_-8px_color-mix(in_srgb,var(--color-brand)_60%,transparent)] hover:bg-[color:var(--color-brand-600)] hover:-translate-y-0.5',
    secondary:
        'bg-[color:var(--color-surface-2)] text-ink border border-[color:var(--color-border)] hover:border-[color:var(--color-brand)] hover:-translate-y-0.5',
    ghost: 'text-ink hover:bg-[color:var(--color-surface-2)]',
    danger: 'text-[#fb7185] hover:bg-[#e11d48]/10',
};

export function Button({ variant = 'primary', size = 'md', className = '', as: Tag = 'button', ...props }) {
    const classes = [
        'inline-flex items-center justify-center gap-2 font-medium rounded-md transition duration-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[color:var(--color-brand)] disabled:opacity-60 disabled:pointer-events-none',
        SIZES[size] ?? SIZES.md,
        VARIANTS[variant] ?? VARIANTS.primary,
        className,
    ].join(' ');

    if (Tag === 'a') {
        return <a className={classes} {...props} />;
    }

    return <button type={props.type ?? 'button'} className={classes} {...props} />;
}

export function Card({ className = '', ...props }) {
    return <div className={`card ${className}`} {...props} />;
}

const fieldBase =
    'w-full rounded-md border bg-[color:var(--color-bg-soft)] px-3.5 py-2.5 text-sm text-ink placeholder:text-[color:var(--color-faint)] transition duration-300 focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/40 focus:border-[color:var(--color-brand)]';

export function Field({ label, error, hint, children, htmlFor }) {
    return (
        <div>
            {label && (
                <label htmlFor={htmlFor} className="mb-1.5 block text-sm font-medium text-ink">
                    {label}
                </label>
            )}
            {children}
            {hint && !error && <p className="mt-1.5 text-xs text-[color:var(--color-faint)]">{hint}</p>}
            {error && <p className="mt-1.5 text-xs text-[#fb7185]">{error}</p>}
        </div>
    );
}

export function TextInput({ error, className = '', ...props }) {
    return (
        <input
            className={`${fieldBase} ${error ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'} ${className}`}
            {...props}
        />
    );
}

export function Textarea({ error, className = '', ...props }) {
    return (
        <textarea
            className={`${fieldBase} resize-y ${error ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'} ${className}`}
            {...props}
        />
    );
}

export function Select({ error, className = '', ...props }) {
    return (
        <select
            className={`${fieldBase} ${error ? 'border-[#e11d48]' : 'border-[color:var(--color-border)]'} ${className}`}
            {...props}
        />
    );
}

export function Checkbox({ className = '', ...props }) {
    return (
        <input
            type="checkbox"
            className={`rounded border-[color:var(--color-border)] text-[color:var(--color-brand)] focus:ring-[color:var(--color-brand)]/40 ${className}`}
            {...props}
        />
    );
}

export function Alert({ tone = 'status', children }) {
    if (!children) return null;

    const tones = {
        status: 'border-[color:var(--color-brand)]/40 bg-[color:var(--color-brand)]/10 text-ink',
        error: 'border-[#e11d48]/40 bg-[#e11d48]/10 text-[#fb7185]',
        warning: 'border-[#f59e0b]/40 bg-[#f59e0b]/10 text-ink',
    };

    return (
        <div className={`mb-6 rounded-md border px-4 py-3 text-sm ${tones[tone] ?? tones.status}`} role={tone === 'error' ? 'alert' : 'status'}>
            {children}
        </div>
    );
}

export function Badge({ tone = 'default', children }) {
    const tones = {
        default: 'border-[color:var(--color-border)] text-[color:var(--color-muted)]',
        brand: 'border-[color:var(--color-brand)]/40 text-[color:var(--color-brand-400)]',
        muted: 'border-[color:var(--color-border)] text-[color:var(--color-faint)]',
    };

    return (
        <span className={`inline-flex whitespace-nowrap rounded-full border px-2.5 py-1 text-xs ${tones[tone] ?? tones.default}`}>
            {children}
        </span>
    );
}
