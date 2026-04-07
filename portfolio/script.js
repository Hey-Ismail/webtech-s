/* typing animation */

const typingElement = document.getElementById("typing-text");

const phrases = [
  "A Front-End Web Developer",

  "An HTML & CSS Enthusiast",

  "A JavaScript Learner",

  "A Creative Problem Solver",
];

let phraseIndex = 0;

let charIndex = 0;

let isDeleting = false;

let typingSpeed = 80;

function typeText() {
  var currentPhrase = phrases[phraseIndex];

  if (isDeleting) {
    typingElement.textContent = currentPhrase.substring(0, charIndex - 1);

    charIndex--;

    typingSpeed = 40;
  } else {
    typingElement.textContent = currentPhrase.substring(0, charIndex + 1);

    charIndex++;

    typingSpeed = 80;
  }

  if (!isDeleting && charIndex === currentPhrase.length) {
    typingSpeed = 1500;

    isDeleting = true;
  } else if (isDeleting && charIndex === 0) {
    isDeleting = false;

    phraseIndex = (phraseIndex + 1) % phrases.length;

    typingSpeed = 400;
  }

  setTimeout(typeText, typingSpeed);
}

typeText();

/* project dynamic rendering */

const projects = [
  {
    title: "Retro Landing Page",

    desc: "A simple retro style landing page using HTML and CSS",

    img: "https://picsum.photos/300?1",
  },

  {
    title: "Todo App",

    desc: "JavaScript todo app with local storage",

    img: "https://picsum.photos/300?2",
  },

  {
    title: "Student Dashboard",

    desc: "Dashboard UI design practice project",

    img: "https://picsum.photos/300?3",
  },
];

const container = document.getElementById("project-container");

projects.forEach((project) => {
  const card = document.createElement("div");

  card.classList.add("card");

  card.innerHTML = `

<img src="${project.img}" width="100%">

<h3>${project.title}</h3>

<p>${project.desc}</p>

`;

  container.appendChild(card);
});

/* dark mode */

const btn = document.getElementById("theme-btn");

btn.addEventListener("click", () => {
  document.body.classList.toggle("dark");

  localStorage.setItem(
    "theme",

    document.body.classList.contains("dark") ? "dark" : "light",
  );
});

if (localStorage.getItem("theme") === "dark") {
  document.body.classList.add("dark");
}

/* form validation */

document
  .getElementById("contact-form")

  .addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value;

    const email = document.getElementById("email").value;

    const subject = document.getElementById("subject").value;

    const message = document.getElementById("message").value;

    const msg = document.getElementById("form-msg");

    if (!name || !email || !subject || !message) {
      msg.textContent = "All fields required";

      msg.style.color = "red";

      return;
    }

    if (!email.includes("@")) {
      msg.textContent = "Invalid email";

      msg.style.color = "red";

      return;
    }

    msg.textContent = "Message sent ✔";

    msg.style.color = "green";

    e.target.reset();
  });
