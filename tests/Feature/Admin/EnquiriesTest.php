<?php

namespace Tests\Feature\Admin;

use App\Models\ContactRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as AssertInertia;
use Tests\TestCase;

class EnquiriesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    private function enquiry(array $overrides = []): ContactRequest
    {
        return ContactRequest::create(array_merge([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'How does this work?',
            'emailed_at' => now(),
        ], $overrides));
    }

    public function test_the_screens_require_a_session(): void
    {
        $enquiry = $this->enquiry();

        $this->get(route('admin.enquiries.index'))->assertRedirect(route('login'));
        $this->get(route('admin.enquiries.show', $enquiry))->assertRedirect(route('login'));
        $this->get(route('admin.enquiries.download'))->assertRedirect(route('login'));
    }

    public function test_the_index_lists_enquiries_with_their_channel(): void
    {
        $this->enquiry(['name' => 'Organic Olly', 'referrer' => 'https://www.google.com/']);
        $this->enquiry(['name' => 'Paid Pat', 'utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'spring']);
        $this->enquiry(['name' => 'Direct Dee']);

        $this->actingAs($this->admin())
            ->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertInertia(fn (AssertInertia $page) => $page
                ->component('Admin/Enquiries/Index')
                ->has('enquiries', 3)
                ->where('enquiries.0.name', 'Direct Dee')
                ->where('enquiries.0.channel', 'Direct')
                ->where('enquiries.1.channel', 'Paid search')
                ->where('enquiries.1.utm_campaign', 'spring')
                ->where('enquiries.2.channel', 'Organic search')
                ->where('meta.total', 3)
                ->has('channels', 3)
            );
    }

    public function test_the_index_can_be_searched(): void
    {
        $this->enquiry(['name' => 'Jane Smith']);
        $this->enquiry(['name' => 'Bob Jones', 'agency' => 'Coastal Homes']);

        $this->actingAs($this->admin())
            ->get(route('admin.enquiries.index', ['q' => 'coastal']))
            ->assertInertia(fn (AssertInertia $page) => $page
                ->has('enquiries', 1)
                ->where('enquiries.0.name', 'Bob Jones')
                ->where('search', 'coastal')
            );
    }

    public function test_the_show_screen_has_the_full_message_and_attribution(): void
    {
        $enquiry = $this->enquiry([
            'message' => "Line one\nLine two",
            'landing_page' => 'https://corexweb.co.za/pricing?utm_source=google',
            'gclid' => 'abc123',
            'emailed_at' => null,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.enquiries.show', $enquiry))
            ->assertOk()
            ->assertInertia(fn (AssertInertia $page) => $page
                ->component('Admin/Enquiries/Show')
                ->where('enquiry.message', "Line one\nLine two")
                ->where('enquiry.gclid', 'abc123')
                ->where('enquiry.channel', 'Paid search')
                ->where('enquiry.emailed', false)
            );
    }

    public function test_the_csv_download_contains_every_enquiry_and_its_channel(): void
    {
        $this->enquiry(['name' => 'Jane Smith', 'utm_source' => 'google', 'utm_medium' => 'cpc']);
        $this->enquiry(['name' => 'Bob Jones']);

        $response = $this->actingAs($this->admin())->get(route('admin.enquiries.download'));

        $response->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertDownload('contact-enquiries-'.now()->format('Y-m-d').'.csv');

        $csv = $response->streamedContent();

        $this->assertStringContainsString('id,received_at,name,email', $csv);
        $this->assertStringContainsString('Jane Smith', $csv);
        $this->assertStringContainsString('Bob Jones', $csv);
        $this->assertStringContainsString('"Paid search"', $csv);
        $this->assertStringContainsString('Direct', $csv);
    }

    public function test_the_dashboard_counts_enquiries(): void
    {
        $this->enquiry();

        $old = $this->enquiry();
        $old->created_at = now()->subDays(45);
        $old->save();

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertInertia(fn (AssertInertia $page) => $page
                ->where('enquiryCount', 2)
                ->where('enquiriesLast30Days', 1)
            );
    }
}
