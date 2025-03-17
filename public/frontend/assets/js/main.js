$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 700) {
            $(".scroll-top-button").fadeIn();
        } else {
            $(".scroll-top-button").fadeOut();
        }
    });

    $(function () {
        $(document).scroll(function () {
            var $nav = $(".navbar");
            $nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height());
        });
    });

    $(".scroll-top-button").on("click", function () {
        $("html , body").animate(
            {
                scrollTop: 0,
            },
            100
        );
    });

    $(".hamburger").click(function () {
        $(".hamburger").toggleClass("active");
        $(".nav-contain").toggleClass("active-nav");
        $("body").toggleClass("overflowNone");
    });

    $("[data-fancybox]").fancybox({
        selector: '[data-fancybox="images"]',
        loop: true,
    });

    var swiper = new Swiper(".swiper-brands", {
        spaceBetween: 10,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        breakpoints: {
            300: {
                slidesPerView: 2,
                spaceBetween: 18,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 18,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 18,
            },
            1024: {
                slidesPerView: 6,
                spaceBetween: 18,
            },
        },
    });

    var swiper = new Swiper(".swiper-offers", {
        spaceBetween: 10,
        loop: false,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            300: {
                slidesPerView: 1,
                spaceBetween: 18,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 18,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 18,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 18,
            },
        },
    });

    var swiper = new Swiper(".swiper-partners", {
        spaceBetween: 10,
        loop: false,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            300: {
                slidesPerView: 2,
                spaceBetween: 5,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 18,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 18,
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 18,
            },
        },
    });

    var swiper = new Swiper(".swiper-blogs", {
        spaceBetween: 10,
        loop: false,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            300: {
                slidesPerView: 1,
                spaceBetween: 18,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 18,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 18,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 18,
            },
        },
    });

    var swiper = new Swiper(".swiper-header", {
        spaceBetween: 10,
        slidesPerView: 1,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    $(".show-pass").on("click", function (event) {
        event.preventDefault();

        $(this).toggleClass("active");
    });

    $(".show_hide_password .show-pass").on("click", function (event) {
        event.preventDefault();
        if ($(this).siblings("input").attr("type") == "text") {
            $(this).siblings("input").attr("type", "password");
        } else if ($(this).siblings("input").attr("type") == "password") {
            $(this).siblings("input").attr("type", "text");
        }
    });

    $(".next-step").click(function () {
        var activeTab = $(".nav-tabs .nav-link.active");
        var nextTab = activeTab.closest("li").next().find("a");
        var prevTab = activeTab.closest("li").prev().find("a");
        prevTab.parent().addClass("done");
        nextTab.tab("show");
    });

    $(".prev-step").click(function () {
        var activeTab = $(".nav-tabs .nav-link.active");
        var prevTab = activeTab.closest("li").prev().find("a");
        prevTab.tab("show");
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#imagePreview").css(
                    "background-image",
                    "url(" + e.target.result + ")"
                );
                $("#imagePreview").hide();
                $("#imagePreview").fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imageUpload").change(function () {
        readURL(this);
    });

    $(".file__input--file_worker").on("change", function (event) {
        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            $(
                "<div class='file__value'><div class='file__value--text'>" +
                file.name +
                "</div><div class='file__value--remove' data-id='" +
                file.name +
                "' ></div></div>"
            ).insertAfter("#file__input_worker");
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".countdown-card").forEach((card) => {
        let days = parseInt(card.getAttribute("data-days"));
        let hours = parseInt(card.getAttribute("data-hours"));
        let minutes = parseInt(card.getAttribute("data-minutes"));
        let seconds = parseInt(card.getAttribute("data-seconds"));

        function updateCountdown() {
            if (seconds > 0) {
                seconds--;
            } else {
                if (minutes > 0) {
                    minutes--;
                    seconds = 59;
                } else if (hours > 0) {
                    hours--;
                    minutes = 59;
                    seconds = 59;
                } else if (days > 0) {
                    days--;
                    hours = 23;
                    minutes = 59;
                    seconds = 59;
                }
            }

            card.querySelector(".days").innerText = days;
            card.querySelector(".hours").innerText = hours;
            card.querySelector(".minutes").innerText = minutes;
            card.querySelector(".seconds").innerText = seconds;
        }

        setInterval(updateCountdown, 1000);
    });
});
