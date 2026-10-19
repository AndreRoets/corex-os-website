<?php

namespace Tests\Feature;

use App\Mail\PricingEnquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PricingEnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_large_agency_enquiry_is_emailed_to_the_demo_inbox(): void
    {
        Mail::fake();

        $this->post(route('pricing.enquire'), [
            'name' => 'M. Naidoo',
            'agency' => 'Ridge Realty',
            'email' => 'm.naidoo@example.com',
            'phone' => '082 000 0000',
            'agents' => '65',
            'branches' => '4',
            'message' => 'Three provinces, one head office.',
            'consent' => '1',
        ])->assertRedirect(route('pricing').'#calculator');

        Mail::assertSent(PricingEnquiry::class, function (PricingEnquiry $mail) {
            return $mail->hasTo(config('mail.demo.address'))
                && $mail->hasReplyTo('m.naidoo@example.com')
                && $mail->enquiry['agency'] === 'Ridge Realty';
        });
    }

    public function test_the_pricing_page_renders_the_enquiry_box(): void
    {
        $this->seed();

        $this->get(route('pricing'))
            ->assertOk()
            ->assertSee(route('pricing.enquire'))
            ->assertSee('Enquire about 31+ agents');
    }

    public function test_the_pricing_page_publishes_the_band_ladder_and_not_the_old_plans(): void
    {
        $this->seed();

        $this->get(route('pricing'))
            ->assertOk()
            ->assertSeeInOrder(['R450', 'R900', 'R1,495', 'R2,495', 'R3,295', 'R3,995', 'R4,595', 'R5,095', 'On quotation'])
            ->assertSeeInOrder(['R299 per agent', 'R250 per agent', 'R220 per agent', 'R200 per agent', 'R184 per agent', 'R170 per agent'])
            ->assertSee('Everything included, no setup fees')
            ->assertSee('R750')
            ->assertDontSee('CoreX Team')
            ->assertDontSee('CoreX Agency')
            ->assertDontSee('R295')
            ->assertDontSee('R195');
    }

    public function test_a_honeypot_submission_is_rejected_and_sends_nothing(): void
    {
        Mail::fake();

        $this->post(route('pricing.enquire'), [
            'name' => 'Bot',
            'agency' => 'Bot Co',
            'email' => 'bot@example.com',
            'consent' => '1',
            'website' => 'http://spam.example',
        ])->assertSessionHasErrors('website');

        Mail::assertNothingSent();
    }
}
