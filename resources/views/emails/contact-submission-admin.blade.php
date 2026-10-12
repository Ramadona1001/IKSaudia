New contact form submission

Reference: {{ $submission->reference_number }}
Name: {{ $submission->name }}
Email: {{ $submission->email ?: '—' }}
Phone: {{ $submission->phone ?: '—' }}
Company: {{ $submission->company ?: '—' }}
Subject: {{ $submission->subject ?: '—' }}

Message:
{{ $submission->message }}
@if (is_array($submission->custom_fields) && $submission->custom_fields !== [])

Additional fields:
@foreach ($submission->custom_fields as $key => $value)
{{ $key }}: {{ is_scalar($value) ? $value : json_encode($value) }}
@endforeach
@endif

Review in admin:
{{ $adminUrl }}
