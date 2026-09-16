@component('mail::message')
# New website enquiry

**{{ $enquiry->name }}**@if ($enquiry->agency) from **{{ $enquiry->agency }}**@endif sent a message through the contact page.

@component('mail::table')
| | |
|:--|:--|
| **Name** | {{ $enquiry->name }} |
| **Agency** | {{ $enquiry->agency ?: '—' }} |
| **Email** | [{{ $enquiry->email }}](mailto:{{ $enquiry->email }}) |
| **Phone** | {{ $enquiry->phone ?: '—' }} |
| **About** | {{ $enquiry->topicLabel() ?: '—' }} |
@endcomponent

**Message**

{{ $enquiry->message }}

@component('mail::button', ['url' => 'mailto:'.$enquiry->email])
Reply to {{ $enquiry->name }}
@endcomponent

**Where they came from**

@component('mail::table')
| | |
|:--|:--|
| **Channel** | {{ $channel }} |
| **Source / medium** | {{ $enquiry->utm_source ?: '—' }} / {{ $enquiry->utm_medium ?: '—' }} |
| **Campaign** | {{ $enquiry->utm_campaign ?: '—' }} |
| **Landing page** | {{ $enquiry->landing_page ?: '—' }} |
| **Referrer** | {{ $enquiry->referrer ?: '—' }} |
@endcomponent

They consented to being contacted via the website form. [Open in the admin console]({{ $adminUrl }}).
@endcomponent
