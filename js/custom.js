/**
 * ============================================
 * TOP COLLEGES INDIA - CUSTOM JAVASCRIPT
 * ============================================
 *
 * This file contains all custom JavaScript functionality for the website:
 * - Mobile navigation menu (Mean Menu)
 * - Scroll to top button
 * - Animated number counters
 * - Page preloader
 *
 * NOTE: Main slider and testimonials use Bootstrap 3 Carousel (see index.html)
 * ============================================
 */

(function ($) {
  "use strict";
  $(document).ready(function () {
    // ===================================
    // MOBILE NAVIGATION
    // ===================================
    $("#main-menu").meanmenu({
      meanScreenWidth: "767",
      meanMenuContainer: ".mobile-nav-menu",
    });

    // ===================================
    // SCROLL TO TOP BUTTON
    // ===================================
    if ($.fn.scrollUp) {
      $.scrollUp({
        scrollText: '<i class="fa fa-long-arrow-up"></i>',
        easingType: "linear",
        scrollSpeed: 900,
        animation: "fade",
      });
    }

    // ===================================
    // ANIMATED COUNTERS
    // ===================================
    if ($.fn.counterUp) {
      $(".counter").counterUp({
        delay: 10,
        time: 1000,
      });
    }

    // ===================================
    // PAGE PRELOADER
    // ===================================
    $(window).on("load", function () {
      $("#preloader").fadeOut();
      $("#preloader-status").delay(200).fadeOut("slow");
      $("body").delay(200).css({ "overflow-x": "hidden" });
    });
  });
})(jQuery);
