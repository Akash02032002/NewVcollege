<?php
/**
 * ============================================
 * TOP COLLEGES INDIA - Main Landing Page
 * ============================================
 * 
 * This file includes all the modular sections of the homepage.
 * Each section is maintained in a separate file for better organization.
 * 
 * Sections Directory: /sections/
 * 
 * Structure:
 * - head.php          : HTML head, meta tags, CSS links
 * - header.php        : Navigation bar and top banner
 * - hero-slider.php   : Main carousel slider and daily updates
 * - courses.php       : Course offerings (B.Ed, M.Ed, D.El.Ed)
 * - college-list.php  : Complete list of colleges
 * - counters.php      : Statistics counters (246, 416, 648, 824)
 * - faq-events.php    : FAQ accordion and events section
 * - testimonials.php  : Student testimonials carousel
 * - footer.php        : Footer with contact info and links
 * - modals.php        : Modal forms (application/contact)
 * - scripts.php       : JavaScript files and closing tags
 * ============================================
 */

// Include Head Section (HTML head, meta tags, CSS links, opening body tag)
include 'sections/head.php';

// Include Header Section (Navigation & Top Banner)
include 'sections/header.php';

// Include Hero Slider Section (Main Carousel & Daily Updates)
include 'sections/hero-slider.php';

// Include Courses Section (B.Ed, M.Ed, D.El.Ed)
include 'sections/courses.php';

// Include College List Section (All Colleges)
include 'sections/college-list.php';

// Include Counters Section (Statistics)
include 'sections/counters.php';

// Include FAQ & Events Section
include 'sections/faq-events.php';

// Include Testimonials Section
include 'sections/testimonials.php';

// Include Footer Section
include 'sections/footer.php';

// Include Modals Section (Application Forms)
include 'sections/modals.php';

// Include Scripts Section (JavaScript files & closing tags)
include 'sections/scripts.php';

?>

