import { Link, router, usePage } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import { Alert, Button, Card } from '../../../Components/UI';

export default function UsersIndex({ users }) {
    const { errors } = usePage().props;

    const destroy = (user) => {
        if (! confirm(`Delete ${user.name}? This cannot be undone.`)) return;
        router.delete(route('admin.users.destroy', user.id));
    };

    return (
        <AdminLayout
            title="Users"
            heading={
                <div className="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-semibold text-ink">Users</h1>
                        <p className="mt-1 text-sm text-[color:var(--color-muted)]">Everyone who can sign in to this console.</p>
                    </div>
                    <Button as="a" href={route('admin.users.create')}>New user</Button>
                </div>
            }
        >
            <Alert tone="error">{errors?.user}</Alert>

            <Card className="overflow-x-auto">
                <table className="w-full min-w-[32rem] text-left text-sm">
                    <thead>
                        <tr className="border-b border-[color:var(--color-border)] text-xs uppercase tracking-wider text-[color:var(--color-faint)]">
                            <th className="px-5 py-3 font-medium">Name</th>
                            <th className="px-5 py-3 font-medium">Email</th>
                            <th className="px-5 py-3 font-medium text-right">
                                <span className="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {users.map((user) => (
                            <tr key={user.id} className="border-b border-[color:var(--color-border-soft)] last:border-0">
                                <td className="px-5 py-4 font-medium text-ink">{user.name}</td>
                                <td className="px-5 py-4 text-[color:var(--color-muted)]">{user.email}</td>
                                <td className="px-5 py-4">
                                    <div className="flex items-center justify-end gap-3">
                                        <Link href={route('admin.users.edit', user.id)} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-ink">
                                            Edit
                                        </Link>
                                        <button onClick={() => destroy(user)} className="text-sm text-[color:var(--color-muted)] transition duration-300 hover:text-[#fb7185]">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </Card>
        </AdminLayout>
    );
}
