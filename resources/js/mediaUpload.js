/**
 * Uploads an image to the admin media endpoint and resolves with { url, name }.
 *
 * A plain fetch rather than an Inertia visit: the endpoint returns JSON for a
 * JS-driven picker to consume immediately, not a page to navigate to.
 */
export async function uploadMedia(file) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    const formData = new FormData();
    formData.append('file', file);

    const response = await fetch(route('admin.media.store'), {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, Accept: 'application/json' },
        credentials: 'same-origin',
        body: formData,
    });

    if (! response.ok) {
        throw new Error('Upload failed');
    }

    return response.json();
}
