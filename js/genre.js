document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const genre = urlParams.get("genre");

    const genreTitle = document.getElementById("genreTitle");
    genreTitle.textContent = genre + " Movies";

    const movieList = document.getElementById("movieList");
    movieList.innerHTML = ""; // Clear previous content

    let filteredMovies;

    if (genre === "All") {
        filteredMovies = movies;
    } else {
        filteredMovies = movies.filter(movie => {
            const genres = movie.genres.split(", "); // Split genres into an array
            return genres.includes(genre); // Check if the genre is in the array
        });
    }

    if (filteredMovies.length > 0) {
        filteredMovies.forEach(movie => {
            const movieCard = document.createElement("div");
            movieCard.classList.add("movie-card");

            // Movie title and image
            movieCard.innerHTML = `
                <img src="${movie.image}" alt="${movie.title}" width="150">
                <h3 class="movie-title">${movie.title}</h3>
                <p class="movie-description" style="display: none;">${movie.description}</p>
            `;

            // Add click event to toggle description
            const movieTitle = movieCard.querySelector(".movie-title");
            const movieDescription = movieCard.querySelector(".movie-description");

            movieTitle.addEventListener("click", () => {
                if (movieDescription.style.display === "none") {
                    movieDescription.style.display = "block";
                } else {
                    movieDescription.style.display = "none";
                }
            });

            movieList.appendChild(movieCard);
        });
    } else {
        movieList.innerHTML = "<p>No movies found in this genre.</p>";
    }
});