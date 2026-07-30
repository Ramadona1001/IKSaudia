<?php

/*
|--------------------------------------------------------------------------
| Frequently Asked Questions
|--------------------------------------------------------------------------
|
| FAQ items are grouped by category. Each item provides per-locale
| question/answer text. Run `php artisan db:seed --class=FaqSeeder` to
| import this file into the database for admin CRUD at /ik-admin/faqs.
|
*/

return [

    'categories' => [
        [
            'key' => 'services',
            'icon' => 'bi-gear-fill',
            'color' => 'gold',
            'title' => [
                'en' => 'Services & Capabilities',
                'ar' => 'الخدمات والقدرات',
            ],
            'items' => [
                [
                    'question' => [
                        'en' => 'What manufacturing services does IK Saudi Manufacturing provide?',
                        'ar' => 'ما هي خدمات التصنيع التي تقدمها IK للتصنيع السعودي؟',
                    ],
                    'answer' => [
                        'en' => 'We provide comprehensive industrial manufacturing including structural steel fabrication, pressure vessel manufacturing (ASME Sec. VIII), heat exchangers (TEMA), storage tanks (API 650/620), piping systems, modular skid packages, precision CNC machining, and full EPC project execution.',
                        'ar' => 'نقدم تصنيعًا صناعيًا شاملاً يشمل تصنيع الفولاذ الهيكلي وأوعية الضغط (ASME القسم الثامن) ومبادلات الحرارة (TEMA) وخزانات التخزين (API 650/620) وأنظمة الأنابيب والحزم المعيارية والتشغيل الدقيق CNC وتنفيذ مشاريع EPC الكاملة.',
                    ],
                ],
                [
                    'question' => [
                        'en' => 'Can IK Saudi handle both small fabrication orders and large EPC projects?',
                        'ar' => 'هل يمكن لـ IK السعودي التعامل مع طلبات التصنيع الصغيرة ومشاريع EPC الكبيرة؟',
                    ],
                    'answer' => [
                        'en' => 'Yes. We scale from precision machining of individual components to full EPC projects valued at hundreds of millions of Saudi Riyals. Our project management capability covers all scales — from single equipment items to complete industrial plant construction.',
                        'ar' => 'نعم. ننطلق من التشغيل الدقيق للمكونات الفردية إلى مشاريع EPC الكاملة بقيمة مئات الملايين من الريالات السعودية. تغطي قدرتنا في إدارة المشاريع جميع الأحجام.',
                    ],
                ],
                [
                    'question' => [
                        'en' => 'Do you offer maintenance contracts for existing industrial facilities?',
                        'ar' => 'هل تقدمون عقود صيانة للمنشآت الصناعية القائمة؟',
                    ],
                    'answer' => [
                        'en' => 'Yes. We offer multi-year Operation & Maintenance (O&M) contracts including planned preventive maintenance (PPM), corrective maintenance, plant shutdowns, turnarounds, and 24/7 emergency response.',
                        'ar' => 'نعم. نقدم عقود تشغيل وصيانة (O&M) متعددة السنوات تشمل الصيانة الوقائية المخططة والصيانة التصحيحية وإيقاف المصانع والتحولات والاستجابة الطارئة على مدار الساعة.',
                    ],
                ],
            ],
        ],

        [
            'key' => 'quality',
            'icon' => 'bi-patch-check-fill',
            'color' => 'blue',
            'title' => [
                'en' => 'Quality & Standards',
                'ar' => 'الجودة والمعايير',
            ],
            'items' => [
                [
                    'question' => [
                        'en' => 'What certifications and quality standards do you hold?',
                        'ar' => 'ما هي الشهادات ومعايير الجودة التي تحملونها؟',
                    ],
                    'answer' => [
                        'en' => 'IK Saudi Manufacturing holds: ISO 9001:2015 (QMS), ISO 14001:2015 (Environmental), ISO 45001:2018 (Safety), ASME U/U2/S/PP Stamps, AWS D1.1/D1.5 Structural Welding, API Q1 (Quality Program), Aramco AVL and SABIC ASL registrations.',
                        'ar' => 'تحمل IK للتصنيع السعودي: ISO 9001:2015 وISO 14001:2015 وISO 45001:2018 وأختام ASME U/U2/S/PP ولحام AWS D1.1/D1.5 وAPI Q1 وقائمة موردي أرامكو المعتمدين (AVL) وقائمة موردي سابك (ASL).',
                    ],
                ],
                [
                    'question' => [
                        'en' => 'What NDT methods do your quality inspectors use?',
                        'ar' => 'ما هي طرق الاختبار غير المُتلف التي يستخدمها مفتشو الجودة لديكم؟',
                    ],
                    'answer' => [
                        'en' => 'Our ASNT Level II/III certified inspectors perform UT, RT, MT, PT, VT, and PMI. We also facilitate third-party inspection by Bureau Veritas, Lloyd\'s, SGS, and ARAMCO-appointed TPI agencies.',
                        'ar' => 'يقوم مفتشونا المعتمدون من ASNT Level II/III بإجراء UT وRT وMT وPT وVT وPMI. كما نسهّل الفحص من جهات خارجية مثل Bureau Veritas وLloyd\'s وSGS وجهات TPI المعتمدة من أرامكو.',
                    ],
                ],
            ],
        ],

        [
            'key' => 'projects',
            'icon' => 'bi-kanban-fill',
            'color' => 'gold',
            'title' => [
                'en' => 'Projects & Delivery',
                'ar' => 'المشاريع والتسليم',
            ],
            'items' => [
                [
                    'question' => [
                        'en' => 'How do you ensure on-time project delivery?',
                        'ar' => 'كيف تضمنون تسليم المشاريع في الوقت المحدد؟',
                    ],
                    'answer' => [
                        'en' => 'We use Primavera P6 for scheduling with weekly look-ahead plans, critical path method (CPM) analysis, and earned value management (EVM). Our on-time delivery rate is 96%.',
                        'ar' => 'نستخدم Primavera P6 للجدولة مع خطط أسبوعية مسبقة وتحليل المسار الحرج (CPM) وإدارة القيمة المكتسبة (EVM). معدل التسليم في الوقت المحدد لدينا 96%.',
                    ],
                ],
                [
                    'question' => [
                        'en' => 'What is the typical lead time for fabrication orders?',
                        'ar' => 'ما هو وقت التسليم المعتاد لطلبات التصنيع؟',
                    ],
                    'answer' => [
                        'en' => 'Small precision components: 2–6 weeks. Pressure vessels and heat exchangers: 8–20 weeks. Structural steel packages: 6–16 weeks. Full modular skid systems: 16–36 weeks.',
                        'ar' => 'المكونات الدقيقة الصغيرة: 2-6 أسابيع. أوعية الضغط ومبادلات الحرارة: 8-20 أسبوعًا. حزم الفولاذ الهيكلي: 6-16 أسبوعًا. أنظمة السكيد المعيارية الكاملة: 16-36 أسبوعًا.',
                    ],
                ],
                [
                    'question' => [
                        'en' => 'Do you work with international clients outside Saudi Arabia?',
                        'ar' => 'هل تعملون مع عملاء دوليين خارج المملكة العربية السعودية؟',
                    ],
                    'answer' => [
                        'en' => 'Yes. We regularly export manufactured equipment to UAE, Kuwait, Qatar, Bahrain, Oman, Egypt, and beyond. We handle full export documentation, SABER/SASO compliance, and international shipping coordination.',
                        'ar' => 'نعم. نصدّر بانتظام المعدات المصنّعة إلى الإمارات والكويت وقطر والبحرين وعُمان ومصر وغيرها. نتولى توثيق التصدير الكامل والامتثال لـ SABER/SASO وتنسيق الشحن الدولي.',
                    ],
                ],
            ],
        ],

        [
            'key' => 'contact',
            'icon' => 'bi-envelope-fill',
            'color' => 'blue',
            'title' => [
                'en' => 'Contact & Support',
                'ar' => 'التواصل والدعم',
            ],
            'items' => [
                [
                    'question' => [
                        'en' => 'How do I request a quote?',
                        'ar' => 'كيف أطلب عرض سعر؟',
                    ],
                    'answer' => [
                        'en' => 'Submit your inquiry via our Contact page, email sales@iksaudimanufacturing.com, or call our hotline. Please include drawings, specifications, material requirements, and required delivery date. We respond within 48 working hours.',
                        'ar' => 'أرسل استفسارك عبر صفحة "تواصل معنا"، أو راسلنا على sales@iksaudimanufacturing.com، أو اتصل بخطنا الساخن. يرجى تضمين الرسومات والمواصفات ومتطلبات المواد وتاريخ التسليم المطلوب. نرد خلال 48 ساعة عمل.',
                    ],
                ],
                [
                    'question' => [
                        'en' => 'Do you have a 24/7 emergency contact number?',
                        'ar' => 'هل لديكم رقم اتصال للطوارئ على مدار الساعة؟',
                    ],
                    'answer' => [
                        'en' => 'Yes. Our Emergency Response Hotline operates 24/7. For maintenance contract clients a dedicated number is provided in your service agreement with guaranteed response times of 2–4 hours for critical issues.',
                        'ar' => 'نعم. خط الاستجابة للطوارئ يعمل على مدار الساعة. لعملاء عقود الصيانة يتم تقديم رقم مخصص في اتفاقية الخدمة مع أوقات استجابة مضمونة من 2 إلى 4 ساعات للحالات الحرجة.',
                    ],
                ],
            ],
        ],

    ],

];
