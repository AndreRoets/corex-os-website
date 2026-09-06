import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Button, Card, Field, TextInput } from '../../../Components/UI';

export default function UserEdit({ user }) {
    const { data, setData, put, processing, errors } = useForm({
        name: user.name,
        email: user.email,
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('admin.users.update', user.id));
    };

    return (
        <AdminLayout
            title={user.name}
            heading={<h1 className="text-2xl font-semibold text-ink">{user.name}</h1>}
        >
            <form onSubmit={submit} className="max-w-md">
                <Card className="space-y-4 p-6">
                    <Field label="Name" htmlFor="name" error={errors.name}>
                        <TextInput id="name" value={data.name} error={errors.name} onChange={(e) => setData('name', e.target.value)} />
                    </Field>
                    <Field label="Email" htmlFor="email" error={errors.email}>
                        <TextInput id="email" type="email" autoComplete="username" value={data.email} error={errors.email} onChange={(e) => setData('email', e.target.value)} />
                    </Field>
                    <Field label="New password" htmlFor="password" error={errors.password} hint="Leave blank to keep the current password.">
                        <TextInput id="password" type="password" autoComplete="new-password" value={data.password} error={errors.password} onChange={(e) => setData('password', e.target.value)} />
                    </Field>

                    <Button type="submit" className="w-full" disabled={processing}>
                        Save
                    </Button>
                </Card>
            </form>
        </AdminLayout>
    );
}
