<x-mail::message>
# New Contact Message

You received a new message from the GRID LMS landing page.

---

**Name:** {{ $contactMessage->name }}

**Email:** {{ $contactMessage->email }}

**Message:**

{{ $contactMessage->message }}

---

<small>Submitted from IP: {{ $contactMessage->ip_address }} on {{ $contactMessage->created_at->format('F j, Y \\a\\t g:i A') }}</small>
</x-mail::message>
