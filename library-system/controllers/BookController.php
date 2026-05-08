<?php

require_once __DIR__ . '/../models/BookModel.php';

function book_response($success, $message, $data = [])
{
    return [
        'success' => $success,
        'message' => $message,
        'data' => $data,
    ];
}

function book_clean_text($value)
{
    return trim((string) $value);
}

function book_normalize_status($status)
{
    $allowedStatuses = ['Available', 'Borrowed', 'Reserved'];

    return in_array($status, $allowedStatuses, true) ? $status : 'Available';
}

function book_list_controller()
{
    return book_response(true, 'Books loaded successfully.', [
        'books' => book_fetch_all(),
    ]);
}

function book_single_controller($payload)
{
    $bookId = isset($payload['book_id']) ? (int) $payload['book_id'] : 0;

    if ($bookId <= 0) {
        return book_response(false, 'Please choose a valid book.');
    }

    $book = book_fetch_by_id($bookId);

    if (!$book) {
        return book_response(false, 'Book not found.');
    }

    return book_response(true, 'Book loaded successfully.', [
        'book' => $book,
    ]);
}

function book_store_controller($payload)
{
    $bookId = isset($payload['book_id']) ? (int) $payload['book_id'] : 0;
    $title = book_clean_text($payload['title'] ?? '');
    $authorName = book_clean_text($payload['author_name'] ?? '');
    $category = book_clean_text($payload['category'] ?? '');
    $availabilityStatus = book_normalize_status(book_clean_text($payload['availability_status'] ?? 'Available'));

    if ($title === '' || $authorName === '' || $category === '') {
        return book_response(false, 'Please fill in every book field.');
    }

    if ($bookId > 0) {
        if (!book_fetch_by_id($bookId)) {
            return book_response(false, 'The selected book was not found.');
        }

        $updated = book_update($bookId, $title, $authorName, $category, $availabilityStatus);

        if (!$updated) {
            return book_response(false, 'Unable to update the selected book.');
        }

        return book_response(true, 'Book updated successfully.', [
            'book_id' => $bookId,
        ]);
    }

    $insertId = book_insert($title, $authorName, $category, $availabilityStatus);

    if ($insertId === false) {
        return book_response(false, 'Unable to save the book.');
    }

    return book_response(true, 'Book added successfully.', [
        'book_id' => $insertId,
    ]);
}

function book_delete_controller($payload)
{
    $bookId = isset($payload['book_id']) ? (int) $payload['book_id'] : 0;

    if ($bookId <= 0) {
        return book_response(false, 'Please select a valid book to delete.');
    }

    if (!book_fetch_by_id($bookId)) {
        return book_response(false, 'The selected book no longer exists.');
    }

    $deleted = book_delete($bookId);

    if (!$deleted) {
        return book_response(false, 'Unable to delete the selected book.');
    }

    return book_response(true, 'Book deleted successfully.', [
        'book_id' => $bookId,
    ]);
}


//git commit -m "Lab task -12"