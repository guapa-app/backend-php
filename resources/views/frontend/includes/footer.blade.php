<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 mb-4">
                <div class="contain">
                    <a href="#" class="brand-name">
                        <img src="{{ asset('frontend/assets/images/logo/logo.svg') }}" loading="lazy" alt=""/>
                    </a>

                    <p>
                        سجل باستخدام عنوان بريدك الإلكتروني لتبقى على اطلاع بالخصومات والتحديثات الجديدة من جميع
                        الحملات!
                    </p>

                    <form action="" class="form-icon">
                        <div class="form-group">
                            <img src="{{ asset('frontend/assets/images/icons/search.svg') }}" loading="lazy" alt=""/>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="ادخل بريدك الالكتروني"/>

                            <a href="#" class="custom-btn secondary-btn">
                    <span>
                      سجل الان
                    </span>
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <div class="col-lg-4 col-12 mb-4">
                <div class="flex-data">
                    <div class="contain">
                        <h2>
                            روابط مهمة
                        </h2>

                        <ul class="links">
                            <li>
                                <a href="{{ route('download-app') }}"><span>تطبيق مزودي الخدمة</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('about-app') }}"><span>تطبيق المستخدمين</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('about')}}"><span>عن قوابا</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('blogs')}}"><span>المدونة</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="contain">
                        <h2>
                            للتواصل
                        </h2>

                        <ul class="links">
                            <li>
                                <a href="tel:5314343889" class="block">
                      <span>
                        للتواصل تليفونياً
                      </span>

                                    <span>
                        9665314343889
                      </span>
                                </a>
                            </li>

                            <li>
                                <a href="mailto:info@guapa.com.sa" class="block">
                      <span>
                        البريد الالكتروني
                      </span>

                                    <span>
                        info@guapa.com.sa
                      </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-12 mb-4">
                <div class="contain padding-contain">
                    <h2>
                        تابعنا
                    </h2>

                    <ul class="links">
                        <li>
                            <a href="https://www.tiktok.com/@guapaapp">
                                <img src="#" loading="lazy"
                                     alt=""/>

                                <span>
                      TikTok
                    </span>
                            </a>
                        </li>

                        <li>
                            <a href="https://x.com/Guapaapp">
                                <img src="{{ asset('frontend/assets/images/footer/x.svg') }}" loading="lazy" alt=""/>

                                <span>
                      منصة إكس
                    </span>
                            </a>
                        </li>

                        <li>
                            <a href="https://www.linkedin.com/in/guapa-app-b38b3031a/">
                                <img src="{{ asset('frontend/assets/images/footer/linked.svg') }}" loading="lazy"
                                     alt=""/>

                                <span>
                      لينكدين
                    </span>
                            </a>
                        </li>

                        <li>
                            <a href="https://www.instagram.com/Guapaapp/">
                                <img src="{{ asset('frontend/assets/images/footer/insta.svg') }}" loading="lazy"
                                     alt=""/>

                                <span>
                      انستغرام
                    </span>
                            </a>
                        </li>

                        <li>
                            <a href="https://snapchat.com/t/6Gx8Jq8a">
                                <img src="#" loading="lazy"
                                     alt=""/>
                                <span>
                      سناب شات
                    </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="copyrights">
            <p>
                © 2024 جوابا. جميع الحقوق محفوظة.
            </p>

            <ul class="links">
                <li>
                    <a href="#">
                        إعدادات ملفات تعريف الارتباط
                    </a>
                </li>

                <li>
                    <a href="{{ route('user-terms') }}">
                        شروط الخدمة
                    </a>
                </li>

                <li>
                    <a href="{{ route('privacy-policy') }}">
                        سياسة الخصوصية
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>
