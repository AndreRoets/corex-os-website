<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as AssertInertia;
use Tests\TestCase;

/**
 * The only way an admin account is created or removed from the web UI — see
 * App\Http\Controllers\Admin\UserController. The guard rails here (no
 * deleting the last account, no deleting yourself) exist because this
 * console has no self-registration and no emailed password reset: deleting
 * either of those accounts by mistake would lock everyone out with no way
 * back in short of the CLI.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_users_screens_require_a_session(): void
    {
        $user = User::factory()->create();

        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->get(route('admin.users.create'))->assertRedirect(route('login'));
        $this->post(route('admin.users.store'), [])->assertRedirect(route('login'));
        $this->get(route('admin.users.edit', $user))->assertRedirect(route('login'));
        $this->put(route('admin.users.update', $user), [])->assertRedirect(route('login'));
        $this->delete(route('admin.users.destroy', $user))->assertRedirect(route('login'));
    }

    public function test_a_new_user_can_be_created(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'password' => 'a-long-enough-passphrase',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertTrue(User::where('email', 'new-admin@example.com')->exists());
    }

    public function test_a_short_password_is_refused(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'password' => 'short',
        ])->assertSessionHasErrors('password');
    }

    public function test_an_email_can_only_belong_to_one_account(): void
    {
        $admin = User::factory()->create();
        $existing = User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Admin',
            'email' => 'taken@example.com',
            'password' => 'a-long-enough-passphrase',
        ])->assertSessionHasErrors('email');
    }

    public function test_a_user_can_be_updated_without_changing_the_password(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->create(['name' => 'Old name']);
        $originalHash = $target->password;

        $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name' => 'New name',
            'email' => $target->email,
            'password' => '',
        ])->assertRedirect(route('admin.users.index'));

        $target->refresh();
        $this->assertSame('New name', $target->name);
        $this->assertSame($originalHash, $target->password);
    }

    public function test_a_user_can_be_updated_with_a_new_password(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->create();
        $originalHash = $target->password;

        $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name' => $target->name,
            'email' => $target->email,
            'password' => 'a-new-long-enough-passphrase',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertNotSame($originalHash, $target->fresh()->password);
    }

    public function test_a_user_can_delete_another_user(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $target))
            ->assertRedirect(route('admin.users.index'));

        $this->assertModelMissing($target);
    }

    public function test_a_user_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create();
        User::factory()->create(); // so "last user" isn't also in play here

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHasErrors('user');

        $this->assertModelExists($admin);
    }

    public function test_the_last_remaining_user_cannot_be_deleted(): void
    {
        $this->assertSame(0, User::count());
        $onlyUser = User::factory()->create();

        $this->actingAs($onlyUser)
            ->delete(route('admin.users.destroy', $onlyUser))
            ->assertSessionHasErrors('user');

        $this->assertModelExists($onlyUser);
    }

    public function test_the_index_lists_users_without_exposing_password_hashes(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertInertia(fn (AssertInertia $page) => $page
                ->component('Admin/Users/Index')
                ->has('users', 1)
                ->missing('users.0.password')
            );
    }
}
