import { useRef, useState } from 'react';
import { uploadMedia } from '../mediaUpload';
import { Button, TextInput } from './UI';

/**
 * A URL field an admin can either type into directly (paste an existing
 * image URL) or fill by uploading a file, which then round-trips through
 * the same field — so the form never needs to know which path was used.
 */
export default function ImagePicker({ label, value, onChange, error }) {
    const [uploading, setUploading] = useState(false);
    const fileInput = useRef(null);

    const pick = async (e) => {
        const file = e.target.files?.[0];
        e.target.value = '';
        if (! file) return;

        setUploading(true);
        try {
            const { url } = await uploadMedia(file);
            onChange(url);
        } catch {
            alert('The image could not be uploaded. Please try again.');
        } finally {
            setUploading(false);
        }
    };

    return (
        <div>
            {label && <label className="mb-1.5 block text-sm font-medium text-ink">{label}</label>}
            <div className="flex items-center gap-3">
                {value && (
                    <img src={value} alt="" className="h-12 w-12 shrink-0 rounded-md border border-[color:var(--color-border)] object-cover" />
                )}
                <TextInput
                    value={value ?? ''}
                    error={error}
                    placeholder="https://…"
                    onChange={(e) => onChange(e.target.value)}
                    className="flex-1"
                />
                <Button
                    type="button"
                    variant="secondary"
                    size="sm"
                    className="whitespace-nowrap"
                    disabled={uploading}
                    onClick={() => fileInput.current?.click()}
                >
                    {uploading ? 'Uploading…' : 'Upload'}
                </Button>
                <input ref={fileInput} type="file" accept="image/*" onChange={pick} disabled={uploading} className="hidden" />
            </div>
            {error && <p className="mt-1.5 text-xs text-[#fb7185]">{error}</p>}
        </div>
    );
}
