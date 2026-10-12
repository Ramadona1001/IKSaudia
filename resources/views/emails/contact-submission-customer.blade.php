@if ($locale === 'ar')
مرحباً {{ $submission->name }}،

شكراً لتواصلك مع IK Saudi. تم استلام رسالتك وسنرد عليك في أقرب وقت.

رقم المرجع: {{ $submission->reference_number }}
@if ($submission->subject)
الموضوع: {{ $submission->subject }}
@endif

مع تحيات
IK Saudi
@else
Hello {{ $submission->name }},

Thank you for contacting IK Saudi. We have received your message and will reply as soon as possible.

Reference: {{ $submission->reference_number }}
@if ($submission->subject)
Subject: {{ $submission->subject }}
@endif

Kind regards,
IK Saudi
@endif
