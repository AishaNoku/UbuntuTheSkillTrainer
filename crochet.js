let progress = 0;

// Change progress bar visually
document.getElementById("progress-fill").style.width = progress + "%";
document.getElementById("progress-text").textContent = progress + "% Completed";

// Page redirection for modules 
function openModule(moduleNumber) {
    alert("Opening Module " + moduleNumber);
}
