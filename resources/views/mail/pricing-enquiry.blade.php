@component('mail::message')
# New pricing enquiry — large agency

**{{ $enquiry['name'] }}** from **{{ $enquiry['agency'] }}** is running 40+ agents and asked for a tailored quote.

@component('mail::table')
| | |
|:--|:--|
| **Name** | {{ $enquiry['name'] }} |
| **Agency** | {{ $enquiry['agency'] }} |
| **Email** | [{{ $enquiry['email'] }}](mailto:{{ $enquiry['email'] }}) |
| **Phone** | {{ $enquiry['phone'] ?: '—' }} |
| **Agents** | {{ $enquiry['agents'] ?: '40+' }} |
| **Branches** | {{ $enquiry['branches'] ?: '—' }} |
@endcomponent

@if (! empty($enquiry['message']))
**Message**

{{ $enquiry['message'] }}
@endif

@component('mail::button', ['url' => 'mailto:'.$enquiry['email']])
Reply to {{ $enquiry['name'] }}
@endcomponent

They consented to being contacted via the pricing page.
@endcomponent
