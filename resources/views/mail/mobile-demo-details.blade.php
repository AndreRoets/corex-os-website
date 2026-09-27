@component('mail::message')
# Your demo login

Hi {{ $name }} — here are the details for the CoreX OS mobile app. Install the app, then sign in with these.

@component('mail::table')
| | |
|:--|:--|
| **Email** | {{ $demoEmail }} |
| **Password** | {{ $demoPassword }} |
@endcomponent

@component('mail::button', ['url' => $androidUrl])
Get it on Google Play
@endcomponent

On an iPhone, install it from the [App Store]({{ $iosUrl }}) instead.

**A few things worth knowing**

- This is a shared demo agency with made-up listings, deals and contacts. Change whatever you like — nothing here is anyone's real data.
- Everything you see is white-labelled per agency. Your own colours and logo would replace ours.
- Anyone else trying the demo is in the same agency, so you may see their edits too.

Stuck, or want to see it on your own deals? Reply to this email, or write to [{{ $contactEmail }}](mailto:{{ $contactEmail }}) — a person reads it.

Thanks,<br>
The CoreX OS team
@endcomponent
