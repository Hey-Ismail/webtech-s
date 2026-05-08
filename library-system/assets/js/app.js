(function () {
  const apiUrl = "ajax/book_handler.php";

  const bookForm = document.getElementById("bookForm");
  const bookIdInput = document.getElementById("bookId");
  const bookTitleInput = document.getElementById("bookTitle");
  const bookAuthorInput = document.getElementById("bookAuthor");
  const bookCategoryInput = document.getElementById("bookCategory");
  const bookStatusInput = document.getElementById("bookStatus");
  const submitButton = document.getElementById("submitBtn");
  const cancelButton = document.getElementById("cancelBtn");
  const messageBox = document.getElementById("formMessage");
  const booksTableBody = document.getElementById("booksTableBody");
  const totalBooksCount = document.getElementById("totalBooksCount");
  const availableBooksCount = document.getElementById("availableBooksCount");
  const borrowedBooksCount = document.getElementById("borrowedBooksCount");

  const state = {
    editingId: "",
  };

  let messageTimer = null;

  function escapeHtml(value) {
    return String(value)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#39;");
  }

  function formatDate(value) {
    if (!value) {
      return "N/A";
    }

    const date = new Date(String(value).replace(" ", "T"));

    if (Number.isNaN(date.getTime())) {
      return value;
    }

    return new Intl.DateTimeFormat("en-US", {
      month: "short",
      day: "numeric",
      year: "numeric",
    }).format(date);
  }

  function statusClass(status) {
    const normalized = String(status || "")
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, "-");

    if (!normalized) {
      return "status-default";
    }

    return `status-${normalized}`;
  }

  function showMessage(message, type) {
    messageBox.textContent = message;
    messageBox.className = `alert ${type}`;
    messageBox.hidden = false;

    if (messageTimer) {
      window.clearTimeout(messageTimer);
    }

    messageTimer = window.setTimeout(() => {
      messageBox.hidden = true;
    }, 4000);
  }

  function setSubmitState(isBusy) {
    submitButton.disabled = isBusy;
    submitButton.textContent = isBusy
      ? "Saving..."
      : state.editingId
        ? "Update Book"
        : "Save Book";
  }

  function updateSummary(books) {
    totalBooksCount.textContent = books.length;
    availableBooksCount.textContent = books.filter(
      (book) => book.availability_status === "Available",
    ).length;
    borrowedBooksCount.textContent = books.filter(
      (book) => book.availability_status === "Borrowed",
    ).length;
  }

  function renderEmptyState(message) {
    booksTableBody.innerHTML = `
            <tr>
                <td colspan="7" class="table-placeholder">${escapeHtml(message)}</td>
            </tr>
        `;
  }

  function renderBooks(books) {
    updateSummary(books);

    if (!books.length) {
      renderEmptyState(
        "No book records found. Add the first one using the form.",
      );
      return;
    }

    booksTableBody.innerHTML = "";

    books.forEach((book, index) => {
      const row = document.createElement("tr");

      row.innerHTML = `
                <td>${index + 1}</td>
                <td><strong>${escapeHtml(book.title)}</strong></td>
                <td>${escapeHtml(book.author_name)}</td>
                <td>${escapeHtml(book.category)}</td>
                <td>
                    <span class="status-badge ${statusClass(book.availability_status)}">
                        ${escapeHtml(book.availability_status)}
                    </span>
                </td>
                <td>${escapeHtml(formatDate(book.updated_at || book.created_at))}</td>
            `;

      const actionCell = document.createElement("td");
      actionCell.className = "actions-cell";

      const editButton = document.createElement("button");
      editButton.type = "button";
      editButton.className = "action-btn action-edit";
      editButton.dataset.action = "edit";
      editButton.dataset.book = JSON.stringify(book);
      editButton.textContent = "Edit";

      const deleteButton = document.createElement("button");
      deleteButton.type = "button";
      deleteButton.className = "action-btn action-delete";
      deleteButton.dataset.action = "delete";
      deleteButton.dataset.id = book.id;
      deleteButton.dataset.title = book.title;
      deleteButton.textContent = "Delete";

      actionCell.append(editButton, deleteButton);
      row.append(actionCell);
      booksTableBody.append(row);
    });
  }

  async function postRequest(formData) {
    const response = await fetch(apiUrl, {
      method: "POST",
      body: formData,
    });

    const rawBody = await response.text();

    let payload;

    try {
      payload = JSON.parse(rawBody);
    } catch (error) {
      throw new Error(rawBody || "The server returned an unexpected response.");
    }

    if (!response.ok && payload && payload.message) {
      throw new Error(payload.message);
    }

    return payload;
  }

  async function loadBooks() {
    renderEmptyState("Loading books...");

    try {
      const formData = new FormData();
      formData.set("action", "list");

      const payload = await postRequest(formData);

      if (!payload.success) {
        throw new Error(payload.message || "Unable to load books.");
      }

      renderBooks(payload.data.books || []);
    } catch (error) {
      renderEmptyState("Unable to load the book list right now.");
      showMessage(error.message, "error");
    }
  }

  function resetForm() {
    state.editingId = "";
    bookForm.reset();
    bookStatusInput.value = "Available";
    bookIdInput.value = "";
    submitButton.textContent = "Save Book";
    cancelButton.hidden = true;
    bookTitleInput.focus();
  }

  function startEditing(book) {
    state.editingId = String(book.id || "");
    bookIdInput.value = state.editingId;
    bookTitleInput.value = book.title || "";
    bookAuthorInput.value = book.author_name || "";
    bookCategoryInput.value = book.category || "";
    bookStatusInput.value = book.availability_status || "Available";
    submitButton.textContent = "Update Book";
    cancelButton.hidden = false;

    bookForm.scrollIntoView({ behavior: "smooth", block: "start" });
    bookTitleInput.focus();
  }

  booksTableBody.addEventListener("click", async (event) => {
    const button = event.target.closest("button[data-action]");

    if (!button) {
      return;
    }

    if (button.dataset.action === "edit") {
      startEditing(JSON.parse(button.dataset.book));
      return;
    }

    if (button.dataset.action === "delete") {
      const bookTitle = button.dataset.title || "this book";

      if (!window.confirm(`Delete "${bookTitle}"?`)) {
        return;
      }

      try {
        const formData = new FormData();
        formData.set("action", "delete");
        formData.set("book_id", button.dataset.id);

        const payload = await postRequest(formData);

        if (!payload.success) {
          throw new Error(payload.message || "Unable to delete the book.");
        }

        if (state.editingId === String(button.dataset.id)) {
          resetForm();
        }

        showMessage(payload.message, "success");
        await loadBooks();
      } catch (error) {
        showMessage(error.message, "error");
      }
    }
  });

  bookForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    try {
      setSubmitState(true);

      const formData = new FormData(bookForm);
      formData.set("action", state.editingId ? "update" : "create");
      formData.set("book_id", state.editingId);

      const payload = await postRequest(formData);

      if (!payload.success) {
        throw new Error(payload.message || "Unable to save the book.");
      }

      showMessage(payload.message, "success");
      resetForm();
      await loadBooks();
    } catch (error) {
      showMessage(error.message, "error");
    } finally {
      setSubmitState(false);
    }
  });

  cancelButton.addEventListener("click", () => {
    resetForm();
  });

  loadBooks();
})();

//git commit -m "Lab task -12"
