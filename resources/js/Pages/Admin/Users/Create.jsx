import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Button, Card, Field, TextInput } from '../../../Components/UI';

export default function UserCreate() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('admin.users.store'));
    };

    return (
        <AdminLayout
            title="New user"
            heading={<h1 className="text-2xl font-semibold text-ink">New user</h1>}
        >
            <form onSubmit={submit} className="max-w-md">
                <Card className="space-y-4 p-6">
                    <Field label="Name" htmlFor="name" error={errors.name}>
                        <TextInput id="name" autoFocus value={data.name} error={errors.name} onChange={(e) => setData('name', e.target.value)} />
                    </Field>
                    <Field label="Email" htmlFor="email" error={errors.email}>
                        <TextInput id="email" type="email" autoComplete="username" value={data.email} error={errors.email} onChange={(e) => setData('email', e.target.value)} />
                    </Field>
                    <Field label="Password" htmlFor="password" error={errors.password} hint="At least 12 characters — a long passphrase beats a short, gnarly one.">
                        <TextInput id="password" type="password" autoComplete="new-password" value={data.password} error={errors.password} onChange={(e) => setData('password', e.target.value)} />
                    </Field>

                    <Button type="submit" className="w-full" disabled={processing}>
                        Create user
                    </Button>
                </Card>
            </form>
        </AdminLayout>
    );
}
