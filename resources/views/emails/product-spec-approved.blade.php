@if ($locale === 'ar')
مرحباً {{ $downloadRequest->name }}،

تمت الموافقة على طلب تحميل المواصفات التقنية.

رقم المرجع: {{ $downloadRequest->reference_number }}
المنتج: {{ $productTitle }}

حمّل ملف PDF من الرابط التالي (صالح لمدة 7 أيام):
{{ $downloadUrl }}

مع تحيات
IK Saudi
@else
Hello {{ $downloadRequest->name }},

Your product specification download request has been approved.

Reference: {{ $downloadRequest->reference_number }}
Product: {{ $productTitle }}

Download your PDF from the link below (valid for 7 days):
{{ $downloadUrl }}

Kind regards,
IK Saudi
@endif
