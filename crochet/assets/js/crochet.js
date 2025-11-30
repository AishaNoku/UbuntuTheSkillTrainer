const moduleWeights = {
    1: 15,
    2: 15,
    3: 15,
    4: 15,
    5: 15,
    6: 25
};

// Mark a module as completed
function markModuleComplete(moduleNumber) {
    let progress = JSON.parse(localStorage.getItem("moduleProgress")) || {};

    progress["module" + moduleNumber] = true;

    localStorage.setItem("moduleProgress", JSON.stringify(progress));

    updateTotalProgress();
}


// Calculate total progress
function calculateProgress() {
    let progress = JSON.parse(localStorage.getItem("moduleProgress")) || {};
    let total = 0;

    for (let i = 1; i <= 6; i++) {
        if (progress["module" + i]) {
            total += moduleWeights[i];
        }
    }
    return total;
}


// Update main progress bar
function updateTotalProgress() {
    let progress = calculateProgress();

    document.getElementById("progress-fill").style.width = progress + "%";
    document.getElementById("progress-text").textContent =
        progress + "% Completed";
}


// Update individual module page progress (100% or 0%)
function updateModuleProgress(moduleNumber) {
    let progress = JSON.parse(localStorage.getItem("moduleProgress")) || {};
    let completed = progress["module" + moduleNumber] ? 100 : 0;

    document.getElementById(`module${moduleNumber}-progress-fill`).style.width =
        completed + "%";

    document.getElementById(`module${moduleNumber}-progress-text`).textContent =
        completed + "% Completed";
}


// Run updates after page loads
document.addEventListener("DOMContentLoaded", () => {
    updateTotalProgress();
});



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
      feedback += `<p><strong>${q.toUpperCase()}:</strong> Correct </p>`;
    } else {
      let correctText = {
        q1: "Chain Stitch",
        q2: "Half Double Crochet",
        q3: "Double Crochet",
      };

      feedback += `
        <p>
          <strong>${q.toUpperCase()}:</strong> Incorrect <br>
          Correct Answer: <strong>${correctText[q]}</strong>
        </p>
      `;
    }
  }

  // Show final score
  feedback += `<h3>You scored ${score} out of 3.</h3>`;

  document.getElementById("result").innerHTML = feedback;
}

function checkAnswersModule3() {
  let score = 0;
  let correct = [];

  if (document.querySelector('input[name="q1"]:checked')?.value === "b") {
    score++; 
  } else correct.push("Q1: The correct answer is B (To shape and widen the project)");

  if (document.querySelector('input[name="q2"]:checked')?.value === "b") {
    score++;
  } else correct.push("Q2: The correct answer is B (Single crochet two together)");

  if (document.querySelector('input[name="q3"]:checked')?.value === "a") {
    score++;
  } else correct.push("Q3: The correct answer is A (Holding the yarn the same way)");

  document.getElementById("module3Result").innerHTML =
    "You scored " + score + " out of 3.";

  document.getElementById("module3CorrectAnswers").innerHTML =
    correct.length === 0 ? "Perfect! All answers correct " : correct.join("<br>");
}



