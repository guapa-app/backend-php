@extends('frontend.layouts.app')

@section('title')
    {{ ucfirst(__('about')) }}
@endsection
@section('content')
    <main>
        <section class="sub-header">
            <div class="container">
                <div class="data-contain">
                    <span>
                        عن شركة جوابا
                    </span>

                    <h1>
                        جوابا وسيلة ربط هي الاولى من نوعها في العالم العربي:
                    </h1>

                    <p>
                        "قوابا" هي المنصة الرقمية الرائدة في مجال التجميل والعناية الشخصية، التي تربط بين مقدمي الخدمات
                        والمستخدمين في تجربة شاملة ومبتكرة. توفر المنصة واجهة سهلة الاستخدام لبحث وعرض خدمات التجميل، وتقدم
                        محتوى تعليمي قيم وتعزز الشفافية من خلال تقييمات حقيقية. كما تدعم "قوابا" مقدمي الخدمات بتوفير أدوات
                        فعّالة لتوسيع نطاق أعمالهم، مع الالتزام بالابتكار والاستدامة لتلبية احتياجات المستخدمين وتطلعاتهم.
                    </p>

                    <div class="row">
                        <div class="col-lg-4 col-12 mb-4">
                            <div class="box">
                                <img src="{{ asset('frontend/assets/images/messages/message_1.svg') }}" loading="lazy"
                                    alt="" />

                                <h2>
                                    رسالتنا
                                </h2>

                                <p>
                                    في "قوابا"، نُعيد تعريف تجربة التجميل والعناية الشخصية من خلال الابتكار والجودة
                                    والاهتمام الحقيقي. نقدم لك منصة متكاملة تجمع بين مقدمي خدمات التجميل والمستخدمين، مع
                                    التركيز على تحقيق الراحة والثقة لكل فرد. نلتزم بتقديم جودة عالية واحترافية، وتجربة
                                    مستخدم سلسة وممتعة، ومحتوى تعليمي قيم، مع تعزيز الشفافية والابتكار المستدام. نحن هنا
                                    لدعمك في كل خطوة على طريق الجمال والإشراق، لضمان تجربة تجميلية استثنائية تلبي وتفوق
                                    توقعاتك.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-4 col-12 mb-4">
                            <div class="box">
                                <img src="{{ asset('frontend/assets/images/messages/message_2.svg') }}" loading="lazy"
                                    alt="" />

                                <h2>
                                    غايتنا
                                </h2>

                                <p>
                                    تسعى منصة "قوابا" إلى تمكين الأفراد من تحقيق أقصى درجات الجمال والثقة بالنفس من خلال
                                    توفير منصة شاملة تجمع بين مقدمي خدمات التجميل والمستخدمين، مما يسهل الوصول إلى خدمات
                                    عالية الجودة وتعزيز تجربة العناية الشخصية. نهدف إلى تيسير اتخاذ قرارات مستنيرة عبر
                                    معلومات موثوقة، دعم مقدمي الخدمات في بناء سمعتهم وتوسيع أعمالهم، وتوفير محتوى تعليمي
                                    محدث. هدفنا هو تقديم تجربة مريحة وفعّالة وبناء مجتمع تجميلي مترابط يدعم التفاعل
                                    الإيجابي.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-4 col-12 mb-4">
                            <div class="box">
                                <img src="{{ asset('frontend/assets/images/messages/message_3.svg') }}" loading="lazy"
                                    alt="" />

                                <h2>
                                    رؤيتنا
                                </h2>

                                <p>
                                    "قوابا" نطمح إلى أن نكون الخيار الأول في مجال التجميل والعناية الشخصية من خلال تقديم
                                    تجربة سلسة، مريحة، ومليئة بالقيمة لكل من المستخدمين ومقدمي الخدمات. حيث نهدف إلى تحقيق
                                    توازن مثالي بين الابتكار والاحترافية لتلبية احتياجات الجميع. نسعى لتسهيل الوصول إلى
                                    خدمات التجميل عالية الجودة، وتعزيز الثقة، والابتكار في تقديم الخدمات لتحقيق أعلى درجات
                                    الرضا والجمال لكل فرد. والحصول على المعلومة من اطباء متخصصين، و الإستفادة من العروض
                                    الحصرية.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <img src="{{ asset('frontend/assets/images/icons/sub_header.png') }}" class="sub-header-img opcity-shape"
                loading="lazy" alt="" />
        </section>

        <section class="sliders general-section">
            <div class="container">
                <div class="custom-heading">
                    <h1>
                        موثوق به من قبل أكثر من 200 علامة تجارية
                    </h1>
                </div>

                <div class="swiper swiper-brands">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/images/brands/brand_1.svg') }}" alt="">
                        </div>

                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/images/brands/brand_2.svg') }}" alt="">
                        </div>

                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/images/brands/brand_1.svg') }}" alt="">
                        </div>

                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/images/brands/brand_2.svg') }}" alt="">
                        </div>

                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/images/brands/brand_1.svg') }}" alt="">
                        </div>

                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/images/brands/brand_2.svg') }}" alt="">
                        </div>

                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/images/brands/brand_1.svg') }}" alt="">
                        </div>

                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/images/brands/brand_2.svg') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="information general-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="contain">
                            <span>
                                لمحة عن قوابا
                            </span>

                            <h1>
                                لا نتوقف أبدًا عن التطور
                            </h1>

                            <p>
                                في "قوابا"، نحن ملتزمون بالتطور المستمر لضمان تقديم أفضل تجربة للمستخدمين ومقدمي الخدمات على
                                حد سواء. نعمل على دمج أحدث التقنيات والابتكارات في مجال التجميل والعناية الشخصية، مع تحسين
                                مستمر لواجهتنا وميزاتها بناءً على ملاحظات المستخدمين. بالإضافة إلى ذلك، نقدم محتوى تعليمي
                                مستمر يساهم في تعزيز المعرفة والمهارات لكل من مقدمي الخدمات والمستخدمين. هدفنا هو البقاء في
                                طليعة عالم التجميل من خلال توفير أدوات متطورة وتجارب جديدة تواكب أحدث التطورات وتلبي
                                احتياجات الجميع بكفاءة وفعالية.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <img src="{{ asset('frontend/assets/images/hero.png') }}" loading="lazy" alt="" />
        </section>
    </main>
@endsection
