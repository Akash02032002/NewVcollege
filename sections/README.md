# Sections Directory

This directory contains all the modular components of the Top Colleges India website homepage.

## File Structure

### 1. **head.php**
- HTML DOCTYPE, opening `<html>` and `<head>` tags
- Meta tags (charset, viewport, description, keywords)
- Page title
- Favicon and Apple Touch Icons
- CSS stylesheet links (Bootstrap, Font Awesome, custom CSS)
- Opening `<body>` tag

### 2. **header.php**
- Top announcement banner (marquee)
- Site logo
- Main navigation menu
  - Home
  - Student Portal (with dropdown)
  - Consultant Portal (with dropdown)
  - Contact Us
- Mobile navigation menu
- "Online Register" button

### 3. **hero-slider.php**
- Bootstrap carousel slider (2 slides)
- Daily Updates sidebar
  - PDF links to latest news and updates

### 4. **courses.php**
- Three course service boxes:
  - B.Ed Course (blue)
  - M.Ed Course (green)
  - D.El.Ed Course (yellow)
- NCTE section (Northern Regional Committee info)

### 5. **college-list.php** *(Largest section)*
- Section heading: "List of Top Courses in Indian Colleges 2025"
- Multiple college cards including:
  - College image
  - College name and link
  - Location and courses offered
  - Description excerpt
  - Call Now button
  - Social media icons
  - Read More and Apply Now buttons
- Featured colleges service boxes
- Live Consultant section with filter form

### 6. **faq-events.php**
- FAQ Accordion with common questions:
  - Choosing the right college
  - Admission process
  - Entrance exams
  - College rankings
  - Scholarship opportunities
- Events section:
  - Upcoming education events and seminars
  - Event cards with images and details

### 7. **testimonials.php**
- Student testimonials section
- Bootstrap carousel with 3 testimonials:
  - Anita Gautam
  - Meena Chawla
  - Pooja Bhardwaj
- Carousel indicators and navigation controls

### 8. **footer.php**
- Four footer columns:
  1. Contact Us (address, email, phone)
  2. Quick Links (navigation)
  3. Enquire Now form
  4. Google Maps embed
- Footer bottom with copyright and legal links
- Floating enquiry button

### 9. **modals.php**
- Contact/Application modal form
  - College ID and name (hidden fields)
  - Full Name field
  - Email field
  - Phone field
  - Message textarea
  - Submit button

### 10. **scripts.php**
- JavaScript library includes:
  - jQuery 3.6.0
  - Bootstrap JS
  - CounterUp plugin
  - Mean Menu plugin
  - Custom JS
  - SweetAlert2
- Inline scripts:
  - Apply modal handler
  - Tawk.to chat widget
  - Performance monitoring
- Closing `</body>` and `</html>` tags

---

## Usage

All sections are included in the main `index.php` file in the following order:

```php
include 'sections/head.php';
include 'sections/header.php';
include 'sections/hero-slider.php';
include 'sections/courses.php';
include 'sections/college-list.php';
include 'sections/faq-events.php';
include 'sections/testimonials.php';
include 'sections/footer.php';
include 'sections/modals.php';
include 'sections/scripts.php';
```

## Benefits of Modular Structure

1. **Easy Maintenance**: Edit individual sections without affecting others
2. **Better Organization**: Clear separation of concerns
3. **Reusability**: Sections can be reused across multiple pages
4. **Team Collaboration**: Multiple developers can work on different sections
5. **Debugging**: Easier to locate and fix issues
6. **Clean Code**: Reduced file size and complexity of main index.php

---

**Last Updated**: February 17, 2026
