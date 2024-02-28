document.getElementById('loadQuizForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const quizTitle = document.getElementById('quizTitle').value;

    fetch('http://localhost/monquiz/backend/charger-quiz.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'quiz_title=' + encodeURIComponent(quizTitle)
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('quizContainer').innerHTML = html;
            attachSubmitListener();
        })
        .catch(error => console.error('Error:', error));
});

function attachSubmitListener() {
    document.getElementById('submitAnswers').addEventListener('click', function () {
        const answers = collectAnswers();
        const userName = document.getElementById('userName').value;

        fetch('http://localhost:3001/submit-quiz', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ answers: answers, name: userName })
        })
            .then(response => response.json())
            .then(data => {
                alert("Votre score est : " + data.score);
            })
            .catch(error => console.error('Error:', error));
    });
}

function collectAnswers() {
    let answers = {};
    document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
        const quizId = input.dataset.quizId;
        const questionId = input.name.split('[').pop().split(']')[0];
        const answerId = input.value;
        if (!answers[quizId]) {
            answers[quizId] = {};
        }
        answers[quizId][questionId] = answerId;
    });
    return answers;
}

