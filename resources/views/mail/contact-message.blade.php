@component('mail::message')
# New website enquiry

**{{ $contact['name'] }}** sent a message through the contact form.

@component('mail::table')
| | |
|:--|:--|
| **Name** | {{ $contact['name'] }} |
| **Email** | [{{ $contact['email'] }}](mailto:{{ $contact['email'] }}) |
| **Phone** | {{ $contact['phone'] ?: '—' }} |
@endcomponent

**Message**

{{ $contact['message'] }}

@component('mail::button', ['url' => 'mailto:'.$contact['email']])
Reply to {{ $contact['name'] }}
@endcomponent
@endcomponent
