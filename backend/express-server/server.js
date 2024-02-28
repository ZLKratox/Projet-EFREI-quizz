const express = require('express');
const mysql = require('mysql2/promise');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
const port = 3001;
app.use(cors());
app.use(bodyParser.json());

const dbConfigQuizApp = {
    host: 'localhost',
    user: 'root',
    password: 'mathis*3310',
    database: 'quiz_app',
};

const dbConfigQuizResponses = {
    host: 'localhost',
    user: 'root',
    password: 'mathis*3310',
    database: 'quiz_responses',
};

app.post('/submit-quiz', async (req, res) => {
    const { quizId, answers, name } = req.body;

    try {
        const connQuizResponses = await mysql.createConnection(dbConfigQuizResponses);
        let score = 0;

        for (const questionId in answers) {
            const answerId = answers[questionId];

            if (quizId !== undefined && name !== undefined && questionId !== undefined && answerId !== undefined) {
                await connQuizResponses.execute(`
                    INSERT INTO responses (quiz_id, user_name, question_id, answer_id)
                    VALUES (?, ?, ?, ?)
                `, [quizId, name, questionId, answerId]);
            }
        }


        await connQuizResponses.end();

        res.json({ success: true, score });
    } catch (error) {
        console.error('Error:', error);
        res.status(500).json({ success: false, message: 'Internal server error' });
    }
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
