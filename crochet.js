let progress = 0;

// Change progress bar visually
document.getElementById("progress-fill").style.width = progress + "%";
document.getElementById("progress-text").textContent = progress + "% Completed";

// Page redirection for modules 
function openModule(moduleNumber) {
    alert("Opening Module " + moduleNumber);
    window.location.href = "module" + moduleNumber + ".html";

}

// MODULE 1 PROGRESS
let module1Progress = 0;

document.getElementById("module1-progress-fill").style.width = module1Progress + "%";
document.getElementById("module1-progress-text").textContent =
    module1Progress + "% Completed";

function startModule() {
    alert("Starting Module 1...");
    // Redirect to first lesson
    window.location.href = "lesson1.html";
}

function openLesson(lessonNumber) {
    alert("Opening Lesson " + lessonNumber);
    window.location.href = "lesson" + lessonNumber + ".html";
}

function checkAnswer() {
    const answer = document.getElementById("quizAnswer").value.trim().toLowerCase();
    const feedback = document.getElementById("quizFeedback");

    if (!answer) {
        feedback.style.color = "red";
        feedback.textContent = "Please enter an answer.";
        return;
    }

    if (answer.includes("medium weight") || answer.includes("acrylic") || answer.includes("4")) {
        feedback.style.color = "green";
        feedback.textContent = "Correct! Medium weight Acrylic Yarn is best for beginners.";
    } else {
        feedback.style.color = "red";
        feedback.textContent = "Not quite. Try again!";
    }
}

function submitExercise() {
    const file = document.getElementById("uploadFile").value;
    const reflection = document.getElementById("reflectionText").value.trim();
    const status = document.getElementById("exerciseStatus");

    if (!file || !reflection) {
        status.style.color = "red";
        status.textContent = "Please complete all exercise sections before submitting.";
        return;
    }

    status.style.color = "green";
    status.textContent = "Exercise submitted successfully!";
}

function completeModule() {
    alert("Module 1 marked as complete! You can now proceed to Module 2.");
    window.location.href = "module2.html";
}

function checkAnswer1(ans) {
            let feedback = document.getElementById("feedback");

            if (ans === "5mm" || ans === "acrylic") {
                feedback.innerHTML = "Correct! Well done!";
                feedback.style.color = "green";
            } else {
                feedback.innerHTML = "Try again!";
                feedback.style.color = "red";
            }
        }


