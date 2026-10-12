New product specification download request

Reference: {{ $downloadRequest->reference_number }}
Product: {{ $productTitle }}
Name: {{ $downloadRequest->name }}
Email: {{ $downloadRequest->email }}
Phone: {{ $downloadRequest->phone }}
Company: {{ $downloadRequest->company ?: '—' }}

Review in admin:
{{ $adminUrl }}
