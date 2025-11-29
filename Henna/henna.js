// MODULE SWITCHING
const moduleButtons = document.querySelectorAll(".module-item");
const modules = document.querySelectorAll(".module-content");

moduleButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    const target = btn.dataset.module;

    // Hide welcome card
    document.getElementById("welcomeSection").classList.add("hidden");

    // Remove active from sidebar
    moduleButtons.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");

    // Hide all modules
    modules.forEach(m => m.classList.add("hidden"));

    // Show the selected module
    document.getElementById(target).classList.remove("hidden");
  });
});

// COURSE / DASHBOARD TAB SWITCHING
const chips = document.querySelectorAll(".chip");
const welcome = document.getElementById("welcomeSection");
const dashboard = document.getElementById("dashboardSection");

chips.forEach(chip => {
  chip.addEventListener("click", () => {
    chips.forEach(c => c.classList.remove("active"));
    chip.classList.add("active");

    const view = chip.dataset.view;

    if (view === "course") {
      welcome.classList.remove("hidden");
      dashboard.classList.add("hidden");
    } else {
      welcome.classList.add("hidden");
      dashboard.classList.remove("hidden");
    }

    // Hide modules when switching to dashboard
    modules.forEach(m => m.classList.add("hidden"));
    moduleButtons.forEach(b => b.classList.remove("active"));
  });
});

// MODULE 1 QUIZ GRADING
function gradeModule1Quiz() {
    let score = 0;

    // Correct answers
    const answers = {
        q1: "true",
        q2: "b",
        q3: "true",
        q4: "c"
    };

    // Check answers
    Object.keys(answers).forEach(q => {
        let selected = document.querySelector(`input[name="${q}"]:checked`);
        if (selected && selected.value === answers[q]) {
            score++;
        }
    });

    // Display result
    const result = document.getElementById("module1QuizResult");
    result.innerHTML = `You scored <strong>${score}/4</strong>.`;
    if (score === 4) result.style.color = "green";
    else result.style.color = "red";
}

//  QUIZ 2 GRADING
function gradeModule2Quiz() {
    let score = 0;

    const answers = {
        q1m2: "true",
        q2m2: "c",
        q3m2: "a",
        q4m2: "true"
    };

    Object.keys(answers).forEach(q => {
        let selected = document.querySelector(`input[name="${q}"]:checked`);
        if (selected && selected.value === answers[q]) score++;
    });

    const result = document.getElementById("module2QuizResult");
    result.innerHTML = `You scored <strong>${score}/4</strong>.`;
    result.style.color = score === 4 ? "green" : "red";
}
// QUIZ 3 GRADING
function gradeModule3Quiz() {
    let score = 0;

    const answers = {
        q1m3: "true",
        q2m3: "a",
        q3m3: "true",
        q4m3: "a"
    };

    Object.keys(answers).forEach(q => {
        let selected = document.querySelector(`input[name="${q}"]:checked`);
        if (selected && selected.value === answers[q]) score++;
    });

    const result = document.getElementById("module3QuizResult");
    result.innerHTML = `You scored <strong>${score}/4</strong>.`;
    result.style.color = score === 4 ? "green" : "red";
}

