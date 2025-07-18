(function($) {
  "use strict";

  // Tabs switching
  const tabs = document.querySelectorAll('[data-tab-target]');
  const tabContents = document.querySelectorAll('[data-tab-content]');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = document.querySelector(tab.dataset.tabTarget);
      tabContents.forEach(tabContent => tabContent.classList.remove('active'));
      tabs.forEach(tab => tab.classList.remove('active'));
      tab.classList.add('active');
      target.classList.add('active');
    });
  });



const navLinks = document.querySelectorAll(".nav-link");

navLinks.forEach(link => {
  link.addEventListener("click", function (e) {
    e.preventDefault(); // mencegah #id masuk ke URL

    // Ambil target dari atribut href (misal "#our-art")
    const targetId = this.getAttribute("href");
    const target = document.querySelector(targetId);

    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
      history.replaceState(null, '', window.location.pathname); // hapus # dari URL
    }

    // Tutup menu responsif
    hamburger.classList.remove("active");
    navMenu.classList.remove("responsive");
  });
});


  // Sticky header on scroll
  const initScrollNav = function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 200) {
      $('#header').addClass("fixed-top");
    } else {
      $('#header').removeClass("fixed-top");
    }
  }

  $(window).scroll(function () {
    initScrollNav();
  });

  $(document).ready(function () {
    initScrollNav();

    // Lightbox for images
    Chocolat(document.querySelectorAll('.image-link'), {
      imageSize: 'contain',
      loop: true,
    });

    // Search toggle
    $('#header-wrap').on('click', '.search-toggle', function (e) {
      var selector = $(this).data('selector');
      $(selector).toggleClass('show').find('.search-input').focus();
      $(this).toggleClass('active');
      e.preventDefault();
    });

    // Close search when clicking outside
    $(document).on('click touchstart', function (e) {
      if (!$(e.target).is('.search-toggle, .search-toggle *, #header-wrap, #header-wrap *')) {
        $('.search-toggle').removeClass('active');
        $('#header-wrap').removeClass('show');
      }
    });

    // Slick sliders
    $('.main-slider').slick({
      autoplay: false,
      autoplaySpeed: 2000,
      fade: true,
      dots: true,
      prevArrow: $('.prev'),
      nextArrow: $('.next'),
    });

    $('.ar-slider, .banner-slider').slick({
      autoplay: false,
      autoplaySpeed: 2000,
      fade: true,
      dots: true,
      arrows: false,
    });

    $('.product-grid').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      autoplay: false,
      autoplaySpeed: 2000,
      dots: true,
      arrows: false,
      responsive: [
        {
          breakpoint: 1400,
          settings: { slidesToShow: 3 }
        },
        {
          breakpoint: 999,
          settings: { slidesToShow: 2 }
        },
        {
          breakpoint: 660,
          settings: { slidesToShow: 1 }
        }
      ]
    });

    // AOS animation
    AOS.init({
      duration: 1200,
      once: true,
    });

    // StellarNav
    jQuery('.stellarnav').stellarNav({
      theme: 'plain',
      closingDelay: 250,
    });
  });

})(jQuery);
