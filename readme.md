# Web Development Project Collection

This repository contains several independent web development assignments and practice projects. It includes static front-end work, PHP session demos, AJAX and JSON examples, and a full MVC-style library management system.

## Project Overview

### `portfolio/`

A retro-styled student portfolio built with HTML, CSS, and vanilla JavaScript.

- Dark and light mode toggle.
- Typing animation on the hero section.
- Responsive layout using Flexbox and CSS Grid.
- Contact form with basic JavaScript validation.

### `midterm/`

Lab tasks and practice files covering foundational front-end concepts.

- HTML structure and semantic layout.
- CSS styling with Flexbox and Grid.
- JavaScript DOM manipulation and interactivity.
- Includes `LabTask-01` through `LabTask-09`, plus notes and test files.

### `finalterm/`

Contains two small PHP-based demos.

- `index.php`, `dashboard.php`, and `logout.php` demonstrate PHP sessions for login state.
- `index.html`, `student.php`, and `script.js` demonstrate AJAX requests and JSON response handling.
- `style.css` and `stylee.css` provide the UI styling for those demos.

### `library-system/`

A university library management system built with a basic MVC structure.

- `models/` contains procedural MySQL CRUD functions.
- `controllers/` handles request validation and response logic.
- `views/` contains the HTML interface.
- `ajax/` contains the PHP handler used by AJAX requests.
- `database/` contains the SQL schema and setup script.
- `assets/` contains the CSS and JavaScript for the UI.

### `testTask.html`

A standalone front-end test page.

## Technologies Used

- HTML5
- CSS3
- JavaScript (ES6)
- PHP
- MySQL
- AJAX
- JSON

## How to Run

- Open `portfolio/index.html` or any of the `midterm/LabTask-*.html` files directly in a browser.
- Run the PHP projects through XAMPP or another local server:
  - `finalterm/index.php` for the session demo.
  - `finalterm/index.html` for the AJAX and JSON student demo.
  - `library-system/index.php` for the library management system.
- Import `library-system/database/library_system.sql` into MySQL, or open `library-system/database/setup.php` once to create the database and table.

## Notes

- The repository is organized as a collection of separate assignments, so each folder can be opened and tested independently.
- The PHP projects require Apache and MySQL to be running locally.
