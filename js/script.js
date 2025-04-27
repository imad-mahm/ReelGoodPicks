$(document).ready(function () {
  // REMOVE from watchlist (jQuery)
  $(document).on("click", ".remove-from-watchlist", function (event) {
    event.preventDefault(); // Prevents the default action of the link
    event.stopPropagation(); // Stops the event from propagating to parent elements

    const movieId = $(this).attr("id").split("-")[1]; // Extract the movie ID from the button's id
    console.log("Removing movie ID:", movieId);

    // Make an AJAX request to remove the movie from the watchlist
    $.ajax({
      url: "remove_from_watchlist.php",
      type: "POST",
      data: { movie_id: movieId },
      success: () => {
        $(this).text("Removed!").prop("disabled", true); // Change button text and disable it
      },
      error: (xhr, status, error) => {
        console.error("Error:", error); // Log any error
      },
    });
  });

  // ADD to watchlist (jQuery)
  $(document).on("click", ".add-to-watchlist", function (event) {
    event.preventDefault(); // Prevents the default action of the link
    event.stopPropagation(); // Stops the event from propagating to parent elements

    const movieId = $(this).attr("id").split("-")[1]; // Extract the movie ID from the button's id
    console.log("Adding movie ID:", movieId);

    // Make an AJAX request to add the movie to the watchlist
    $.ajax({
      url: "add_to_watchlist.php",
      type: "POST",
      data: { movie_id: movieId },
      success: () => {
        $(this).text("Added!").prop("disabled", true); // Change button text and disable it
      },
      error: (xhr, status, error) => {
        console.error("Error:", error); // Log any error
      },
    });
  });

  // RANDOM movie button (jQuery AJAX + DOM update)
  $(document).on("click", ".btn-primary", function (event) {
    if ($(this).closest(".movie-card").length) {
      event.preventDefault(); // Prevents the default action of the link

      // Make an AJAX request to fetch a random movie
      $.ajax({
        url: "random.php?ajax=1",
        method: "GET",
        dataType: "json",
        success: (data) => {
          const movie = data.movie;
          const inWatchlist = data.in_watchlist;

          // Update the movie card's content dynamically
          const movieCard = document.querySelector(".movie-card");
          movieCard.innerHTML = `
            <img src="${movie.POSTERURL}" alt="${movie.TITLE}" style="max-width: 300px;">
            <h3>${movie.TITLE}</h3>
            <p><strong>Year:</strong> ${movie.RELEASE_YEAR}</p>
            <p><strong>Director:</strong> ${movie.DIRECTOR}</p>
            <p><strong>Genre:</strong> ${movie.GENRES}</p>
            <p>${movie.DESCRIPTION}</p>
            <div class="button-group">
              <a href="#" class="btn btn-primary">Get Another Movie</a>
              ${
                !inWatchlist
                  ? `<a href="#" class="btn btn-sm btn-primary add-to-watchlist" id="add-${movie.ID}" onclick="event.stopPropagation();">Add to Watchlist</a>`
                  : `<a href="#" class="btn btn-danger remove-from-watchlist" id="remove-${movie.ID}" onclick="event.stopPropagation();">Remove from Watchlist</a>`
              }
            </div>
          `;
        },
        error: (xhr, status, error) => {
          console.error("Error fetching movie:", error); // Log any error
        },
      });
    }
  });

  // ========================
  // Test and swipe functionality 
  // ========================
  if (document.getElementById("testSection")) {
    // Define the questions for the test
    const questions = [
      {
        question: "What's your current mood?",
        options: [
          { text: "I want to laugh", value: "comedy" },
          { text: "I want to be thrilled", value: "action" },
          { text: "I want to feel something deep", value: "drama" },
          { text: "I want to be scared", value: "horror" },
        ],
      },
      {
        question: "What's your preferred movie length?",
        options: [
          { text: "Short (under 90 minutes)", value: "short" },
          { text: "Medium (90-120 minutes)", value: "medium" },
          { text: "Long (over 120 minutes)", value: "long" },
        ],
      },
      {
        question: "What's your preferred movie era?",
        options: [
          { text: "Classic (pre-1980)", value: 1980 },
          { text: "Modern (1980-2010)", value: 2010 },
          { text: "Recent (2010-present)", value: 3000 },
        ],
      },
      {
        question: "What's your preferred movie setting?",
        options: [
          { text: "Real world", value: "realistic fiction" },
          { text: "Fantasy", value: "fantasy" },
          { text: "Sci-fi", value: "sci-fi" },
          { text: "Historical", value: "historical" },
        ],
      },
    ];

    let currentQuestion = 0;
    const answers = {}; // Store user answers

    // Update the progress bar and question number
    function updateProgress() {
      const progress = ((currentQuestion + 1) / questions.length) * 100;
      document.getElementById("progressBar").style.width = `${progress}%`;
      document.getElementById("questionNumber").textContent = `Question ${
        currentQuestion + 1
      } of ${questions.length}`;
    }

    // Display the current question and its options
    function displayQuestion() {
      const question = questions[currentQuestion];
      document.getElementById("questionText").textContent = question.question;

      const optionsContainer = document.getElementById("optionsContainer");
      optionsContainer.innerHTML = "";

      question.options.forEach((option) => {
        const button = document.createElement("button");
        button.className = "option-btn";
        button.textContent = option.text;
        button.onclick = () => selectOption(option.value);
        optionsContainer.appendChild(button);
      });

      updateProgress();
    }

    // Handle option selection and move to the next question
    function selectOption(value) {
      answers[`q${currentQuestion + 1}`] = value; // Store the answer

      if (currentQuestion < questions.length - 1) {
        currentQuestion++;
        displayQuestion();
      } else {
        fetchMovies(); // Fetch movies after all questions are answered
      }
    }

    // Fetch movies based on user answers
    function fetchMovies() {
      fetch("test_and_swipe.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          action: "getMovies",
          mood: answers.q1,
          length: answers.q2,
          era: answers.q3,
          setting: answers.q4,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            startSwipe(data.movies); // Start the swipe functionality if movies are fetched
          } else {
            alert("Failed to fetch movies.");
          }
        })
        .catch((error) => {
          console.error("Error fetching movies:", error); // Log any error
        });
    }

    let currentMovieIndex = 0;
    let movies = []; // Store the fetched movies

    // Start the swipe functionality by displaying the first movie
    function startSwipe(movieList) {
      movies = movieList;
      currentMovieIndex = 0;
      document.getElementById("testSection").style.display = "none"; // Hide test section
      document.getElementById("swipeSection").style.display = "block"; // Show swipe section
      displayCurrentMovie(); // Display the first movie
    }

    // Display the current movie's details
    function displayCurrentMovie() {
      const movie = movies[currentMovieIndex];
      if (!movie) {
        document.getElementById("movieContent").innerHTML = `
          <h2>No more movies!</h2>
          <p>You've gone through all the recommendations.</p>
          <a href="test.html" class="btn btn-primary">Take the test again</a>
        `;
        document.querySelector(".swipe-buttons").style.display = "none"; // Hide swipe buttons if no movies left
        return;
      }

      document.getElementById("movieContent").innerHTML = `
        <img src="${movie.POSTERURL}" alt="${movie.TITLE}" style="max-height: 400px; object-fit: contain;">
        <div class="movie-info">
          <h2 class="movie-title">${movie.TITLE}</h2>
          <div class="movie-data" style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
            <p><strong>Year:</strong> ${movie.RELEASE_YEAR}</p>
            <p><strong>Director:</strong> ${movie.DIRECTOR}</p>
            <p><strong>Duration:</strong> ${movie.DURATION} minutes</p>
            <p><strong>Genres:</strong> ${movie.GENRES}</p>
            <p class="movie-description">${movie.DESCRIPTION}</p>
          </div>
        </div>
      `;
    }

    // Handle the swipe action (like or dislike)
    function handleSwipe(like) {
      if (like && movies[currentMovieIndex]) {
        fetch("test_and_swipe.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            action: "addToWatchlist",
            movie_id: movies[currentMovieIndex].id,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              console.log("Added to watchlist");
            }
          });
      }

      currentMovieIndex++;
      displayCurrentMovie(); // Display the next movie
    }

    // Event listeners for swipe buttons
    document
      .getElementById("yesButton")
      .addEventListener("click", () => handleSwipe(true)); // Like the current movie
    document
      .getElementById("noButton")
      .addEventListener("click", () => handleSwipe(false)); // Dislike the current movie

    displayQuestion(); // Start displaying the questions
  }
});

