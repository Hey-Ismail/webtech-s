const rollInput = document.getElementById("rollInput");
const nameInput = document.getElementById("nameInput");
const addBtn = document.getElementById("addBtn");
const searchInput = document.getElementById("searchInput");
const sortBtn = document.getElementById("sortBtn");
const highlightBtn = document.getElementById("highlightBtn");
const studentList = document.getElementById("studentList");
const totalText = document.getElementById("totalText");
const attendanceText = document.getElementById("attendanceText");

function formatLabel(roll, name) {
  const safeRoll = roll.trim() === "" ? "N/A" : roll.trim();
  const safeName = name.trim();
  return `${safeRoll} - ${safeName}`;
}

function updateAddButtonState() {
  addBtn.disabled = nameInput.value.trim() === "";
}

function updateStats() {
  const items = Array.from(studentList.children);
  const total = items.length;
  const present = items.filter((item) => {
    return item.querySelector(".present-checkbox").checked;
  }).length;
  const absent = total - present;

  totalText.textContent = `Total students: ${total}`;
  attendanceText.textContent = `Present: ${present}, Absent: ${absent}`;
}

function applySearchFilter() {
  const query = searchInput.value.trim().toLowerCase();
  const items = Array.from(studentList.children);

  items.forEach((item) => {
    const studentName = item.dataset.name.toLowerCase();
    item.style.display = studentName.includes(query) ? "flex" : "none";
  });
}

function createStudentItem(roll, name) {
  const li = document.createElement("li");
  li.className = "student-item";
  li.dataset.roll = roll.trim();
  li.dataset.name = name.trim();

  const label = document.createElement("span");
  label.className = "student-label";
  label.textContent = formatLabel(roll, name);

  const presentToggle = document.createElement("label");
  presentToggle.className = "present-toggle";

  const presentCheckbox = document.createElement("input");
  presentCheckbox.type = "checkbox";
  presentCheckbox.className = "present-checkbox";

  const presentText = document.createElement("span");
  presentText.textContent = "Present";

  presentToggle.appendChild(presentCheckbox);
  presentToggle.appendChild(presentText);

  const actions = document.createElement("div");
  actions.className = "item-actions";

  const editBtn = document.createElement("button");
  editBtn.type = "button";
  editBtn.textContent = "Edit";

  const deleteBtn = document.createElement("button");
  deleteBtn.type = "button";
  deleteBtn.className = "delete-btn";
  deleteBtn.textContent = "Delete";

  actions.appendChild(editBtn);
  actions.appendChild(deleteBtn);

  li.appendChild(label);
  li.appendChild(presentToggle);
  li.appendChild(actions);

  presentCheckbox.addEventListener("change", () => {
    li.classList.toggle("present", presentCheckbox.checked);
    updateStats();
  });

  editBtn.addEventListener("click", () => {
    const currentRoll = li.dataset.roll;
    const currentName = li.dataset.name;

    const updatedRoll = prompt("Enter new roll number:", currentRoll);
    if (updatedRoll === null) {
      return;
    }

    const updatedName = prompt("Enter new student name:", currentName);
    if (updatedName === null) {
      return;
    }

    if (updatedName.trim() === "") {
      alert("Student name cannot be empty.");
      return;
    }

    li.dataset.roll = updatedRoll.trim();
    li.dataset.name = updatedName.trim();
    label.textContent = formatLabel(updatedRoll, updatedName);
    applySearchFilter();
  });

  deleteBtn.addEventListener("click", () => {
    const shouldDelete = confirm(
      "Are you sure you want to delete this student?",
    );

    if (!shouldDelete) {
      return;
    }

    li.remove();
    updateStats();
  });

  return li;
}

addBtn.addEventListener("click", () => {
  const roll = rollInput.value;
  const name = nameInput.value;

  if (name.trim() === "") {
    return;
  }

  const item = createStudentItem(roll, name);
  studentList.appendChild(item);

  rollInput.value = "";
  nameInput.value = "";
  updateAddButtonState();
  updateStats();
  applySearchFilter();
});

nameInput.addEventListener("input", updateAddButtonState);

searchInput.addEventListener("input", () => {
  applySearchFilter();
});

sortBtn.addEventListener("click", () => {
  const items = Array.from(studentList.children);

  items.sort((a, b) => {
    return a.dataset.name.localeCompare(b.dataset.name);
  });

  items.forEach((item) => {
    studentList.appendChild(item);
  });

  applySearchFilter();
});

highlightBtn.addEventListener("click", () => {
  const items = Array.from(studentList.children);

  items.forEach((item) => {
    item.classList.remove("highlight");
  });

  if (items.length > 0) {
    items[0].classList.add("highlight");
  }
});

updateAddButtonState();
updateStats();
