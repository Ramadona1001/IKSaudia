New contact form submission

Reference: {{ $submission->reference_number }}

@foreach (\App\Support\ContactForm::submissionLines($submission) as $line)
{{ $line['label'] }}: {{ $line['value'] }}

@endforeach
Review in admin:
{{ $adminUrl }}
