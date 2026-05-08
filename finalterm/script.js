const loadStudentsBtn = document.getElementById("loadStudentsBtn");
const studentOutput = document.getElementById("studentOutput");
const statusText = document.getElementById("statusText");

function escapeHtml(value) {
  return String(value)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#39;");
}

function renderStudents(students) {
  if (!students.length) {
    studentOutput.innerHTML =
      '<p class="empty-state">No student data was returned.</p>';
    return;
  }

  const cards = students
    .map(
      (student) => `
        <article class="student-card">
            <h3>${escapeHtml(student.name)}</h3>
            <ul>
                <li><strong>ID:</strong> ${escapeHtml(student.id)}</li>
                <li><strong>Department:</strong> ${escapeHtml(student.department)}</li>
                <li><strong>CGPA:</strong> ${escapeHtml(student.cgpa)}</li>
            </ul>
        </article>
    `,
    )
    .join("");

  studentOutput.innerHTML = cards;
}

loadStudentsBtn.addEventListener("click", () => {
  statusText.textContent = "Loading student information...";
  studentOutput.innerHTML =
    '<p class="empty-state">Requesting data from PHP...</p>';

  const xhr = new XMLHttpRequest();
  xhr.open("GET", "student.php", true);
  xhr.responseType = "json";

  xhr.onload = () => {
    if (xhr.status === 200 && xhr.response) {
      const data = xhr.response;
      const students = data.students || [];

      renderStudents(students);
      statusText.textContent = `Loaded ${students.length} student record${students.length === 1 ? "" : "s"} from JSON.`;
    } else {
      studentOutput.innerHTML =
        '<p class="empty-state error">Unable to fetch student data.</p>';
      statusText.textContent = "Request failed.";
    }
  };

  xhr.onerror = () => {
    studentOutput.innerHTML =
      '<p class="empty-state error">A network error occurred while fetching data.</p>';
    statusText.textContent = "Network error.";
  };

  xhr.send();
});
