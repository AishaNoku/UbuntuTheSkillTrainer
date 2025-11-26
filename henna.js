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
