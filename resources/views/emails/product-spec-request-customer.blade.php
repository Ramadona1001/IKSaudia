@if (($downloadRequest->locale ?? 'en') === 'ar')
مرحباً {{ $downloadRequest->name }}،

شكراً لطلبك مواصفات المنتج التقنية.

رقم المرجع: {{ $downloadRequest->reference_number }}
المنتج: {{ $productTitle }}

طلبك قيد المراجعة الآن. بعد موافقة فريقنا سنرسل إليك رابط تحميل ملف PDF عبر البريد الإلكتروني.

مع تحيات
IK Saudi
@else
Hello {{ $downloadRequest->name }},

Thank you for requesting the product technical specifications.

Reference: {{ $downloadRequest->reference_number }}
Product: {{ $productTitle }}

Your request is pending review. Once our team approves it, we will email you a secure PDF download link.

Kind regards,
IK Saudi
@endif
