<?php
$pageTitle = $pageTitle ?? 'University Library Management System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A simple MVC-based university library management system with AJAX CRUD operations.">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero">
            <section class="hero-copy">
                <span class="eyebrow">University Library Management System</span>
                <h1>Manage book records without page refreshes.</h1>
                <p>
                    This interface uses a basic MVC layout: the model talks to MySQL using procedural
                    functions, the controller validates requests, and AJAX keeps the table updated in real time.
                </p>
            </section>

            <aside class="hero-panel" aria-label="Library summary metrics">
                <div class="metric">
                    <span>Total Books</span>
                    <strong id="totalBooksCount">0</strong>
                </div>
                <div class="metric">
                    <span>Available</span>
                    <strong id="availableBooksCount">0</strong>
                </div>
                <div class="metric">
                    <span>Borrowed</span>
                    <strong id="borrowedBooksCount">0</strong>
                </div>
            </aside>
        </header>

        <main class="dashboard">
            <section class="card form-card">
                <div class="card-heading">
                    <h2>Add / Update Book</h2>
                    <p>Submit the form to insert a new record or edit an existing one through AJAX.</p>
                </div>

                <div id="formMessage" class="alert" role="status" aria-live="polite" hidden></div>

                <form id="bookForm" class="book-form" autocomplete="off">
                    <input type="hidden" name="book_id" id="bookId">

                    <label class="full-width">
                        <span>Book Title</span>
                        <input type="text" name="title" id="bookTitle" maxlength="150" required>
                    </label>

                    <label>
                        <span>Author Name</span>
                        <input type="text" name="author_name" id="bookAuthor" maxlength="120" required>
                    </label>

                    <label>
                        <span>Category</span>
                        <input type="text" name="category" id="bookCategory" maxlength="100" required>
                    </label>

                    <label class="full-width">
                        <span>Availability Status</span>
                        <select name="availability_status" id="bookStatus" required>
                            <option value="Available">Available</option>
                            <option value="Borrowed">Borrowed</option>
                            <option value="Reserved">Reserved</option>
                        </select>
                    </label>

                    <div class="form-actions full-width">
                        <button type="submit" id="submitBtn" class="primary-btn">Save Book</button>
                        <button type="button" id="cancelBtn" class="secondary-btn" hidden>Cancel Edit</button>
                    </div>
                </form>
            </section>

            <section class="card table-card">
                <div class="card-heading">
                    <h2>All Books</h2>
                    <p>All CRUD actions are handled asynchronously through the dedicated PHP handler.</p>
                </div>

                <div class="table-wrap">
                    <table class="book-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="booksTableBody">
                            <tr>
                                <td colspan="7" class="table-placeholder">Loading books...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>


//git commit -m "Lab task -12"