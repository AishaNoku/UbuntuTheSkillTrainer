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

function completeModule(moduleNumber) {
    let moduleNumberNext = moduleNumber + 1;
    alert("Module " + moduleNumber + " marked as complete! You can now proceed to Module " + moduleNumberNext);
    window.location.href = "module" + moduleNumberNext + ".html";
}


function checkAnswersModule2() {
  let score = 0;
  let feedback = "<h3>Results:</h3>";

  // Correct answers
  const correctAnswers = {
    q1: "b", // Chain Stitch
    q2: "a", // Half Double Crochet
    q3: "b"  // Double Crochet
  };

  // Loop through each question
  for (let q in correctAnswers) {
    let userAnswer = document.querySelector(`input[name="${q}"]:checked`)?.value;

    if (userAnswer === correctAnswers[q]) {
      score++;
      feedback += `<p><strong>${q.toUpperCase()}:</strong> Correct ✔️</p>`;
    } else {
      let correctText = {
        q1: "Chain Stitch",
        q2: "Half Double Crochet",
        q3: "Double Crochet",
      };

      feedback += `
        <p>
          <strong>${q.toUpperCase()}:</strong> Incorrect ❌ <br>
          Correct Answer: <strong>${correctText[q]}</strong>
        </p>
      `;
    }
  }

  // Show final score
  feedback += `<h3>You scored ${score} out of 3.</h3>`;

  document.getElementById("result").innerHTML = feedback;
}



