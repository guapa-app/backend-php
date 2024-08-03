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
      $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
    });
  });

  $(".scroll-top-button").on('click', function () {
    $('html , body').animate({
      scrollTop: 0
    }, 100);
  });

  $('.hamburger').click(function(){
    $('.hamburger').toggleClass('active');
    $('.nav-contain').toggleClass('active-nav');
    $('body').toggleClass('overflowNone')
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

  $(".show-pass").on('click', function(event) {
    event.preventDefault();
    
    $(this).toggleClass("active");
  });
  
  $(".show_hide_password .show-pass").on('click', function(event) {
    event.preventDefault();
    if($(this).siblings("input").attr("type") == "text"){
      $(this).siblings("input").attr('type', 'password');
    }else if($(this).siblings("input").attr("type") == "password"){
      $(this).siblings("input").attr('type', 'text');
    }
  });

  $('.next-step').click(function() {
    var activeTab = $('.nav-tabs .nav-link.active');
    var nextTab = activeTab.closest('li').next().find('a');
    var prevTab = activeTab.closest('li').prev().find('a');
    prevTab.parent().addClass('done'); 
    nextTab.tab('show');
  });


  $('.prev-step').click(function() {
    var activeTab = $('.nav-tabs .nav-link.active');
    var prevTab = activeTab.closest('li').prev().find('a');
    prevTab.tab('show');
  });

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
        $('#imagePreview').hide();
        $('#imagePreview').fadeIn(650);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  
  $("#imageUpload").change(function() {
    readURL(this);
  });

  $('.file__input--file_worker').on('change', function(event) {
    var files = event.target.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      $("<div class='file__value'><div class='file__value--text'>" + file.name + "</div><div class='file__value--remove' data-id='" + file.name + "' ></div></div>").insertAfter('#file__input_worker');
    }
  });

});