<?php

require_once __DIR__ . '/../config/database.php';

function book_fetch_all()
{
    $connection = db_connection();
    $sql = 'SELECT id, title, author_name, category, availability_status, created_at, updated_at FROM books ORDER BY id DESC';
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        return [];
    }

    $books = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }

    mysqli_free_result($result);

    return $books;
}

function book_fetch_by_id($id)
{
    $connection = db_connection();
    $sql = 'SELECT id, title, author_name, category, availability_status, created_at, updated_at FROM books WHERE id = ? LIMIT 1';
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $bookId, $title, $authorName, $category, $availabilityStatus, $createdAt, $updatedAt);

    $book = null;

    if (mysqli_stmt_fetch($stmt)) {
        $book = [
            'id' => $bookId,
            'title' => $title,
            'author_name' => $authorName,
            'category' => $category,
            'availability_status' => $availabilityStatus,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    mysqli_stmt_close($stmt);

    return $book;
}

function book_insert($title, $authorName, $category, $availabilityStatus)
{
    $connection = db_connection();
    $sql = 'INSERT INTO books (title, author_name, category, availability_status) VALUES (?, ?, ?, ?)';
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'ssss', $title, $authorName, $category, $availabilityStatus);
    $executed = mysqli_stmt_execute($stmt);
    $insertId = $executed ? mysqli_insert_id($connection) : false;

    mysqli_stmt_close($stmt);

    return $insertId;
}

function book_update($id, $title, $authorName, $category, $availabilityStatus)
{
    $connection = db_connection();
    $sql = 'UPDATE books SET title = ?, author_name = ?, category = ?, availability_status = ? WHERE id = ?';
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'ssssi', $title, $authorName, $category, $availabilityStatus, $id);
    $executed = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $executed;
}

function book_delete($id)
{
    $connection = db_connection();
    $sql = 'DELETE FROM books WHERE id = ?';
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);
    $executed = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $executed;
}


//git commit -m "Lab task -12"