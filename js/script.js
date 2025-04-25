const questions = [
  {
    question: "How is your mood today?",
    options: ["Happy", "Sad", "Excited", "Relaxed"],
  },
  {
    question: "What is your favorite movie?",
    options: ["Inception", "Titanic", "Avengers", "Interstellar"],
  },
  {
    question: "What genre do you usually enjoy?",
    options: ["Action", "Romance", "Sci-Fi", "Drama", "Comedy", "Horror"],
  },
  {
    question: "Do you prefer movies in Arabic or English?",
    options: ["Arabic", "English", "Both"],
  },
  {
    question: "What rating do you usually look for?",
    options: ["7+", "8+", "9+"],
  },
];

const movies = {
  Comedy: [
    "The Hangover",
    "Superbad",
    "عسل أسود",
    "Al Sayida Al Thaneya",
    "اللمبي 8 جيجا",
  ],
  Action: ["Mad Max: Fury Road", "The Dark Knight", "الجزيرة", "مهمة في تبوك"],
  Drama: [
    "The Insult",
    "The Pursuit of Happyness",
    "Forrest Gump",
    "باب الحديد",
    "الطريق",
  ],
  Romance: ["The Notebook", "Titanic", "أنت عمري", "الخرساء"],
  "Action-Sci-Fi": ["The Matrix", "Inception"],
  "Comedy-Action": ["Very Big Shot"],
  "Comedy-Drama": ["Bghamdet Ain (بغمضة عين)"],
};

let step = 0;
let answers = [];

const questionText = document.getElementById("questionText");
const optionsContainer = document.getElementById("optionsContainer");

function loadQuestion() {
  if (step < questions.length) {
    const currentQuestion = questions[step];
    questionText.textContent = currentQuestion.question;
    optionsContainer.innerHTML = ""; // Clear previous options

    currentQuestion.options.forEach((option) => {
      const button = document.createElement("button");
      button.textContent = option;
      button.classList.add("btn");
      button.addEventListener("click", () => handleAnswer(option));
      optionsContainer.appendChild(button);
    });
  } else {
    const genre = answers[2]; // Genre question index
    alert(
      `Redirecting to swipe game with recommended movies for genre: ${genre}`
    );
    navigateToSwipeGame(genre);
  }
}

function handleAnswer(option) {
  answers.push(option);

  if (step < questions.length - 1) {
    step++;
    loadQuestion();
  } else {
    const genre = answers[2]; // Genre question
    alert(
      `Redirecting to swipe game with recommended movies for genre: ${genre}`
    );
    navigateToSwipeGame(genre);
  }
}

function navigateToSwipeGame(genre) {
  // Redirect to the swipe game with the movie recommendations for the selected genre
  const recommendedMovies = movies[genre] || [];
  localStorage.setItem("recommendedMovies", JSON.stringify(recommendedMovies));
  window.location.href = "swipe.html"; // Change this to the actual URL of your swipe game page
}

const recommendedMovies =
  JSON.parse(localStorage.getItem("recommendedMovies")) || [];
let index = 0;

const movieTitle = document.getElementById("movieTitle");
const yesButton = document.getElementById("yesButton");
const noButton = document.getElementById("noButton");

function displayMovie() {
  if (recommendedMovies.length > 0) {
    movieTitle.textContent = recommendedMovies[index];
  } else {
    movieTitle.textContent = "No movies available";
  }
}

function handleSwipe() {
  if (index < recommendedMovies.length - 1) {
    index++;
    displayMovie();
  } else {
    alert("No more movies to recommend!");
  }
}
if (movieTitle !== null && yesButton !== null && noButton !== null) {
  yesButton.addEventListener("click", handleSwipe);
  noButton.addEventListener("click", handleSwipe);
}

const genreTitle = document.getElementById("genreTitle");
const movieList = document.getElementById("movieList");
if (genreTitle !== null && movieList !== null)
  document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const genre = urlParams.get("genre");

    genreTitle.textContent = genre + " Movies";

    movieList.innerHTML = ""; // Clear previous content

    let filteredMovies;

    if (genre === "All") {
      filteredMovies = moviesAndDesc;
    } else {
      filteredMovies = moviesAndDesc.filter((movie) => {
        const genres = movie.genres.split(", "); // Split genres into an array
        return genres.includes(genre); // Check if the genre is in the array
      });
    }

    if (filteredMovies.length > 0) {
      filteredMovies.forEach((movie) => {
        const movieCard = document.createElement("div");
        movieCard.classList.add("movie-card");

        // Movie title and image
        movieCard.innerHTML = `
              <img src="${movie.image}" alt="${movie.title}" width="150">
              <h3 class="movie-title">${movie.title}</h3>
              <p class="movie-description">${movie.description}</p>
          `;

        movieList.appendChild(movieCard);
      });
    } else {
      movieList.innerHTML = "<p>No movies found in this genre.</p>";
    }
  });

//login logic

let usernameInput = document.getElementById("username");
let passwordInput = document.getElementById("password");

const loginForm = document.getElementById("loginForm");
if (loginForm !== null)
  loginForm.addEventListener("submit", function (event) {
    event.preventDefault();

    username = usernameInput.value;
    password = passwordInput.value;

    const validUsername = "admin";
    const validPassword = "admin";

    if (username === "" || password === "") {
      alert("Please fill in all fields.");
    } else if (username === validUsername && password === validPassword) {
      alert("Login successful! Redirecting to dashboard...");
      window.location.href = "index.html";
    } else if (username !== validUsername && password !== validPassword) {
      alert("Invalid username and password.");
      usernameInput.value = "";
      passwordInput.value = "";
      usernameInput.focus();
    } else if (password !== validPassword) {
      alert("Invalid password.");
      passwordInput.value = "";
      passwordInput.focus();
    } else {
      alert("Invalid username.");
      usernameInput.value = "";
      usernameInput.focus();
    }
  });
const signupForm = document.getElementById("signupForm");
if (signupForm !== null)
  signupForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const fullname = document.getElementById("fullname").value;
    const email = document.getElementById("email").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;

    if (
      fullname === "" ||
      email === "" ||
      username === "" ||
      password === "" ||
      confirmPassword === ""
    ) {
      alert("Please fill in all fields.");
    } else if (password !== confirmPassword) {
      alert("Passwords do not match.");
    } else {
      alert("Signup successful! Redirecting to login page...");
      window.location.href = "index.html";
    }
  });
//end login logic

// Sample movie data (replace with data from backend later)
const moviesAndDesc = [
  {
    title: "Mad Max: Fury Road",
    genres: "Action",
    image: "images/madmax.jfif",
    description:
      "A high-octane action thriller set in a post-apocalyptic wasteland, where Max teams up with Furiosa to escape a tyrannical leader.",
  },
  {
    title: "Avengers: Endgame",
    genres: "Action, Adventure",
    image: "images/avengersendgame.jfif",
    description:
      "The epic conclusion to the Avengers saga, where the heroes assemble to reverse the damage caused by Thanos and restore the universe.",
  },
  {
    title: "Inception",
    genres: "Action, Sci-Fi, Thriller",
    image: "images/inception.jfif",
    description:
      "A mind-bending sci-fi thriller about a thief who enters the dreams of others to steal secrets, but is tasked with planting an idea instead.",
  },
  {
    title: "The Matrix",
    genres: "Action, Sci-Fi",
    image: "images/matrix.jfif",
    description:
      "A groundbreaking sci-fi film about a hacker who discovers the reality of the simulated world and joins the rebellion against machines.",
  },
  {
    title: "الجزيرة",
    genres: "Action, Drama",
    image: "images/elgazeera.jfif",
    description:
      "A gripping action-drama set in the Middle East, exploring themes of survival and resilience in the face of adversity.",
  },
  {
    title: "مهمة في تبوك",
    genres: "Action",
    image: "images/tobuk.jpg",
    description:
      "An action-packed film about a daring mission in the city of Tabuk, filled with suspense and thrilling sequences.",
  },
  {
    title: "John Wick",
    genres: "Action, Thriller",
    image: "images/johnwick.jfif",
    description:
      "A retired hitman seeks vengeance after the death of his dog, leading to a brutal and stylish action-packed journey.",
  },
  {
    title: "Die Hard",
    genres: "Action, Thriller",
    image: "images/diehard.jfif",
    description:
      "A New York cop battles terrorists during a Christmas party in a Los Angeles skyscraper.",
  },
  {
    title: "Mission: Impossible - Fallout",
    genres: "Action, Adventure",
    image: "images/impossible.webp",
    description:
      "Ethan Hunt and his team race against time to prevent a global catastrophe.",
  },
  {
    title: "The Dark Knight",
    genres: "Action, Crime, Drama",
    image: "images/darknight.webp",
    description:
      "Batman faces off against the Joker in a battle for Gotham City's soul.",
  },

  {
    title: "The Hangover",
    genres: "Comedy",
    image: "images/hangover.jfif",
    description:
      "A hilarious comedy about a group of friends who wake up after a wild bachelor party in Las Vegas with no memory of the night before.",
  },
  {
    title: "Superbad",
    genres: "Comedy",
    image: "images/superbad.webp",
    description:
      "A coming-of-age comedy about two high school friends trying to have one last wild night before graduation.",
  },
  {
    title: "Liar Liar",
    genres: "Comedy",
    image: "images/liarliar.jfif",
    description:
      "A funny tale of a lawyer who, due to his son's wish, is forced to tell the truth for 24 hours, leading to chaotic situations.",
  },
  {
    title: "عسل أسود",
    genres: "Comedy, Drama",
    image: "images/asal_aswad.jfif",
    description:
      "A heartwarming comedy-drama about a young man navigating life, love, and family in modern Egypt.",
  },
  {
    title: "اللمبي 8 جيجا",
    genres: "Comedy",
    image: "images/lmby.jpg",
    description:
      "A popular Egyptian comedy about the misadventures of a quirky character known as 'El-Limby'.",
  },
  {
    title: "Bridesmaids",
    genres: "Comedy",
    image: "images/bridesmaids.jfif",
    description:
      "A group of bridesmaids navigate the ups and downs of wedding planning, leading to hilarious and chaotic situations.",
  },

  {
    title: "باب الحديد",
    genres: "Drama",
    image: "images/babelhadid.jfif",
    description:
      "A powerful drama set in Cairo's Bab El Hadid district, exploring the struggles and dreams of its inhabitants.",
  },
  {
    title: "Forrest Gump",
    genres: "Drama, Romance",
    image: "images/forrestgrump.webp",
    description:
      "A heartwarming drama about a man with a low IQ who inadvertently influences several historical events in the 20th century.",
  },
  {
    title: "The Pursuit of Happyness",
    genres: "Drama",
    image: "images/pursuit.jfif",
    description:
      "An inspiring drama based on the true story of Chris Gardner, who overcomes homelessness to achieve success.",
  },
  {
    title: "Interstellar",
    genres: "Drama, Sci-Fi",
    image: "images/interstellar.jfif",
    description:
      "A visually stunning sci-fi epic about a group of astronauts traveling through a wormhole to find a new home for humanity.",
  },
  {
    title: "The Shawshank Redemption",
    genres: "Drama",
    image: "images/shawshank.webp",
    description:
      "A powerful drama about hope and friendship, following two prisoners as they navigate life in Shawshank Prison.",
  },

  {
    title: "Blade Runner",
    genres: "Drama, Sci-Fi",
    image: "images/bladerunner.jpg",
    description:
      "A classic sci-fi film about a blade runner tasked with hunting down rogue replicants in a dystopian future.",
  },
  {
    title: "Her",
    genres: "Drama, Romance, Sci-Fi",
    image: "images/her.jfif",
    description:
      "A futuristic romance about a man who falls in love with an AI assistant, exploring themes of love and technology.",
  },
  {
    title: "The Martian",
    genres: "Sci-Fi, Adventure",
    image: "images/themartian.jfif",
    description:
      "A thrilling sci-fi adventure about an astronaut stranded on Mars who must use his ingenuity to survive and signal Earth.",
  },
  {
    title: "Star Wars",
    genres: "Sci-Fi, Adventure",
    image: "images/starwars.jfif",
    description:
      "An iconic space opera about the battle between the Rebel Alliance and the evil Galactic Empire.",
  },
  {
    title: "Guardians of the Galaxy",
    genres: "Sci-Fi, Adventure, Comedy",
    image: "images/guardians.jfif",
    description:
      "A fun and action-packed adventure about a group of misfits who team up to save the galaxy.",
  },
  {
    title: "Ex Machina",
    genres: "Sci-Fi, Thriller",
    image: "images/exmachina.jfif",
    description:
      "A thought-provoking sci-fi thriller about artificial intelligence and the nature of consciousness.",
  },
  {
    title: "The Conjuring",
    genres: "Horror",
    image: "images/conjuring.jfif",
    description:
      "A chilling horror film based on the true story of paranormal investigators Ed and Lorraine Warren.",
  },
  {
    title: "Get Out",
    genres: "Horror, Thriller",
    image: "images/getout.jpg",
    description:
      "A psychological horror film about a young African-American man who uncovers a disturbing secret while visiting his girlfriend's family.",
  },
  {
    title: "A Quiet Place",
    genres: "Horror, Sci-Fi",
    image: "images/quietplace.jpg",
    description:
      "A family must live in silence to avoid mysterious creatures that hunt by sound.",
  },
  {
    title: "The Shining",
    genres: "Horror",
    image: "images/shining.jfif",
    description:
      "A psychological horror classic about a family's descent into madness while isolated in a haunted hotel.",
  },
  {
    title: "Hereditary",
    genres: "Horror",
    image: "images/hereditary.jfif",
    description:
      "A deeply unsettling horror film about a family haunted by a sinister presence after the death of their secretive grandmother.",
  },

  {
    title: "Back to the Future",
    genres: "Adventure, Sci-Fi",
    image: "images/bttf.jfif",
    description:
      "A time-traveling adventure about a teenager who accidentally travels to the past and must ensure his parents fall in love.",
  },
  {
    title: "The Secret Life of Walter Mitty",
    genres: "Adventure, Comedy",
    image: "images/mitty.jfif",
    description:
      "An uplifting adventure about a daydreamer who embarks on a real-life journey to find a missing photograph.",
  },
  {
    title: "Chef",
    genres: "Adventure, Comedy",
    image: "images/chef.webp",
    description:
      "A heartwarming comedy about a chef who rediscovers his passion for food and life through a cross-country food truck journey.",
  },
  {
    title: "Jumanji: Welcome to the Jungle",
    genres: "Adventure, Comedy",
    image: "images/jumanji.jfif",
    description:
      "A group of teenagers are transported into a video game and must complete the adventure to return to the real world.",
  },
  {
    title: "Indiana Jones and the Raiders of the Lost Ark",
    genres: "Adventure",
    image: "images/indianajones.jfif",
    description:
      "An iconic adventure film about archaeologist Indiana Jones as he races to find the Ark of the Covenant before the Nazis.",
  },

  {
    title: "The Fault in Our Stars",
    genres: "Romance, Drama",
    image: "images/stars.jfif",
    description:
      "A touching romance about two teenagers with cancer who fall in love and embark on a life-changing journey.",
  },
  {
    title: "Titanic",
    genre: "Romance",
    image: "images/titanic.jfif",
    description:
      "A romantic drama about a young aristocratic woman and a penniless artist who fall in love aboard the R.M.S. Titanic, only to face tragedy when the ship meets its fateful end.",
  },
  {
    title: "Venom",
    genres: "Action, Sci-Fi, Thriller",
    image: "images/venom.jfif",
    description:
      "A dark sci-fi action film about a journalist who bonds with an alien symbiote, gaining superhuman powers while struggling to control the entity’s violent impulses.",
  },
  {
    title: "أنت عمري",
    genres: "Romance, Drama",
    image: "images/antaomry.jfif",
    description:
      "A romantic drama about love, sacrifice, and the enduring power of relationships.",
  },
  {
    title: "The Notebook",
    genres: "Romance, Drama",
    image: "images/notebook.jfif",
    description:
      "A timeless love story about a young couple whose relationship is tested by class differences and war.",
  },
];
//end sample data
//for genre.html
const urlParams = new URLSearchParams(window.location.search);
const genre = urlParams.get('genre');
document.getElementById("genreTitle").innerText = genre + " Movies";

movieList = document.getElementById("movieList");

const filtered = moviesAndDesc.filter(movie => movie.genre.includes(genre));

filtered.forEach(movie => {
  const movieCard = document.createElement("div");
  movieCard.className = "card m-3 p-2";
  movieCard.style.maxWidth = "400px";
  movieCard.innerHTML = `
    <img src="${movie.image}" class="card-img-top" alt="${movie.title}">
    <div class="card-body">
      <h5 class="card-title">${movie.title}</h5>
      <p class="card-text">${movie.description}</p>
    </div>`;
  movieList.appendChild(movieCard);
});


  

// Sample watchlist data (replace with data from your backend)
const watchlist = [
  { id: 1, title: "Inception", poster: "inception.jpg" },
  { id: 2, title: "The Dark Knight", poster: "dark_knight.jpg" },
  { id: 3, title: "Interstellar", poster: "interstellar.jpg" },
];

const watchlistContainer = document.getElementById("watchlist");

// Function to display watchlist items
function renderWatchlist() {
  watchlistContainer.innerHTML = "";
  watchlist.forEach((movie) => {
    const movieCard = document.createElement("div");
    movieCard.classList.add("movie-card");

    movieCard.innerHTML = `
      <img src="images/${movie.poster}" alt="${movie.title}">
      <h3>${movie.title}</h3>
      <button onclick="removeFromWatchlist(${movie.id})">Remove</button>
    `;
    watchlistContainer.appendChild(movieCard);
  });
}

// Function to remove a movie from the watchlist
function removeFromWatchlist(movieId) {
  const index = watchlist.findIndex((movie) => movie.id === movieId);
  if (index !== -1) {
    watchlist.splice(index, 1);
    renderWatchlist();
  }
}

function recommendMovie() {
  event.preventDefault();
  const randomIndex = Math.floor(Math.random() * moviesAndDesc.length);
  const randomMovie = moviesAndDesc[randomIndex];

  const resultElement = document.getElementById("movie-result");
  resultElement.innerHTML = `
    <div class="movie-card">
      <img src="${randomMovie.image}" alt="${randomMovie.title}" class="movie-image">
      <h3>${randomMovie.title}</h3>
      <p><strong>Genres:</strong> ${randomMovie.genres}</p>
      <p><strong>Description:</strong> ${randomMovie.description}</p>
    </div>
  `;
}
