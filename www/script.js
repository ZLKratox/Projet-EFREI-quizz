document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('userCheckForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var username = document.getElementById('usernameCheck').value;
        checkUserAndProceed(username);
    });
});

function checkQuizExistence(quizTitle) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://127.0.0.1/check_quiz_existence.php?quiz_title=' + encodeURIComponent(quizTitle), true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            var response = JSON.parse(xhr.responseText);
            if (response.exists) {
                // Si le quiz existe, rediriger vers la page du quiz pour répondre aux questions
                window.location.href = 'page_du_quiz.html?quiz_id=' + response.quiz_id; // Ajustez selon votre besoin
            } else {
                // Si le quiz n'existe pas, rediriger vers la page de création de quiz
                window.location.href = 'creation_quizz.html';
            }
        }
    };
    xhr.send();
}

function checkUserAndProceed(username) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://127.0.0.1/check_user_and_login.php', true); // Assurez-vous que ce fichier existe et est correctement configuré
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            var response = JSON.parse(xhr.responseText);
            if (response.exists) {
                document.getElementById('userCheckForm').style.display = 'none';
                document.getElementById('quizForm').style.display = 'block';
                handleQuizFormSubmission(response.userId); // Gérez la soumission du formulaire de quiz avec l'ID utilisateur
            } else {
                alert("Utilisateur inexistant.");
            }
        }
    };
    xhr.send('username=' + encodeURIComponent(username));
}

function displayQuiz(questions) {
    let quizContainer = document.getElementById('questions-container');
    quizContainer.innerHTML = ''; // Nettoyer le conteneur

    questions.forEach(function(question, questionIndex) {
        let questionElement = document.createElement('div');
        questionElement.classList.add('question');

        let questionText = document.createElement('h3');
        questionText.textContent = question.question_text;
        questionElement.appendChild(questionText);

        question.answers.forEach(function(answer, answerIndex) {
            let label = document.createElement('label');
            let input = document.createElement('input');
            input.type = 'radio';
            input.name = 'question' + questionIndex;
            input.value = answer.answer_id;

            label.appendChild(input);
            label.appendChild(document.createTextNode(answer.answer_text));

            questionElement.appendChild(label);
            questionElement.appendChild(document.createElement('br'));
        });

        quizContainer.appendChild(questionElement);
    });

    // Ajouter le bouton de soumission à la fin
    let submitButton = document.createElement('button');
    submitButton.textContent = 'Soumettre les réponses';
    submitButton.addEventListener('click', submitQuiz);
    quizContainer.appendChild(submitButton);
}


    // Ajouter le bouton de soumission à la fin
    let submitButton = document.createElement('button');
    submitButton.textContent = 'Soumettre les réponses';
    submitButton.addEventListener('click', submitQuiz);
    quizContainer.appendChild(submitButton);

function submitQuiz() {
    let answers = [];
    document.querySelectorAll('.question').forEach((question, index) => {
        let selectedAnswer = document.querySelector(`input[name="question${index}"]:checked`);
        if (selectedAnswer) {
            answers.push({ question_id: selectedAnswer.name.replace('question', ''), answer_id: selectedAnswer.value });
        }
    });

    // Remplacer par l'ID de quiz réel
    let quizId = 'VotreQuizID';

    fetch('http://127.0.0.1/submit_answers.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ quiz_id: quizId, answers: answers })
    })
    .then(response => response.json())
    .then(data => {
        alert('Réponses soumises avec succès');
        // Traiter la réponse du serveur si nécessaire
    })
    .catch(error => {
        console.error('Erreur lors de la soumission des réponses:', error);
    });
}
