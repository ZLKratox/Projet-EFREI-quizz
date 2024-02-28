document.getElementById('addQuestion').addEventListener('click', function () {
    const questionsContainer = document.getElementById('questionsContainer');
    const questionIndex = questionsContainer.children.length + 1;
    const questionHTML = `
        <div>
            <label>Question ${questionIndex} :</label>
            <input type='text' name='question_${questionIndex}' required>
            <div class='answers' id='answers_${questionIndex}'>
                <!-- Les réponses seront ajoutées ici -->
            </div>
            <button type='button' onclick='addAnswer(${questionIndex})'>Ajouter une réponse</button>
        </div>
    `;
    questionsContainer.insertAdjacentHTML('beforeend', questionHTML);
});

function addAnswer(questionIndex) {
    const answersContainer = document.getElementById(`answers_${questionIndex}`);
    const answerIndex = answersContainer.children.length + 1;
    const answerHTML = `
        <div>
            <label>Réponse ${answerIndex} :</label>
            <input type='text' name='answers_${questionIndex}_${answerIndex}' required>
            <input type='radio' name='correct_${questionIndex}' value='${answerIndex}' required> Correct
        </div>
    `;
    answersContainer.insertAdjacentHTML('beforeend', answerHTML);
}

document.getElementById('submitQuiz').addEventListener('click', function () {
    const quizTitle = document.getElementById('quiz_title').value;
    const formData = new FormData();
    formData.append('quiz_title', quizTitle);

    document.querySelectorAll('#questionsContainer > div').forEach((questionDiv, index) => {
        const questionText = questionDiv.querySelector('input[type=text]').value;
        formData.append(`questions[${index}]`, questionText);

        questionDiv.querySelectorAll('.answers > div').forEach((answerDiv, answerIndex) => {
            const answerText = answerDiv.querySelector('input[type=text]').value;
            const isCorrect = answerDiv.querySelector('input[type=radio]').checked;
            formData.append(`answers[${index}][${answerIndex}]`, answerText);
            if (isCorrect) {
                formData.append(`correct[${index}]`, answerIndex + 1);
            }
        });
    });

    fetch('http://localhost/monquiz/backend/creer-quiz.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('message').innerText = data.message;
        })
        .catch(error => console.error('Error:', error));
});
