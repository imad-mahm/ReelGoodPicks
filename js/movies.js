const movieRecommendations = {
    Happy: ['The Hangover', 'Superbad', 'Liar Liar', 'عسل أسود', 'اللمبي 8 جيجا', 'Back to the Future', 'The Matrix'],
    Sad: ['The Pursuit of Happyness', 'Forrest Gump', 'The Fault in Our Stars', 'أنت عمري', 'الخرساء', 'Interstellar', 'Blade Runner'],
    Excited: ['Inception', 'Avengers: Endgame', 'Mad Max: Fury Road', 'الجزيرة', 'مهمة في تبوك', 'Star Wars', 'Guardians of the Galaxy'],
    Relaxed: ['The Secret Life of Walter Mitty', 'The Intern', 'Chef', 'باب الحديد', 'الطريق', 'The Martian', 'Her']
};

function chooseOption(mood) {
    const movies = movieRecommendations[mood] || [];
    document.getElementById('questionContainer').classList.add('hidden');
    const resultContainer = document.getElementById('resultContainer');
    resultContainer.classList.remove('hidden');

    const movieList = document.getElementById('movieList');
    movieList.innerHTML = '';
    movies.forEach(movie => {
        const listItem = document.createElement('li');
        listItem.textContent = movie;
        movieList.appendChild(listItem);
    });
}
