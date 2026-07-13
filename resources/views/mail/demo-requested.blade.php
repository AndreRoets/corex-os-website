@component('mail::message')
# New demo request

**{{ $demo['name'] }}** from **{{ $demo['agency'] }}** asked for a CoreX OS demo.

@component('mail::table')
| | |
|:--|:--|
| **Name** | {{ $demo['name'] }} |
| **Agency** | {{ $demo['agency'] }} |
| **Email** | [{{ $demo['email'] }}](mailto:{{ $demo['email'] }}) |
| **Phone** | {{ $demo['phone'] ?: '—' }} |
| **Agents** | {{ $demo['agents'] ?: '—' }} |
@endcomponent

@if (! empty($demo['message']))
**Message**

{{ $demo['message'] }}
@endif

@component('mail::button', ['url' => 'mailto:'.$demo['email']])
Reply to {{ $demo['name'] }}
@endcomponent

They consented to being contacted via the website form.
@endcomponent
