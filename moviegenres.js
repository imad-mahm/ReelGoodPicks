import "movies.js";
/* const movies = {
  // example of a movie object
  "movie 1": {
    title: "The Hangover",
    year: 2009,
    language: "English",
    director: "Todd Phillips",
    genre: "Comedy",
    rating: 8,
    nationality: "USA",
    mainActor: "Bradley Cooper",
  }*/

// Function to update the movie grid based on the selected genre
function updateMovieGrid(genre) {
  const movieGrid = document.querySelector(".movie-grid");
  movieGrid.innerHTML = ""; // Clear the movie grid

  for (const key in movies) {
    const movie = movies[key];
    if (genre === "all" || movie.genre === genre) {
      const movieCard = document.createElement("div");
      movieCard.classList.add("movie-card");
      movieCard.innerHTML = `
        <img src="images/${key}.jpg" alt="${movie.title}">
        <div class="movie-info">
          <h2>${movie.title}</h2>
          <p>Year: ${movie.year}</p>
          <p>Language: ${movie.language}</p>
          <p>Director: ${movie.director}</p>
          <p>Rating: ${movie.rating}</p>
          <p>Nationality: ${movie.nationality}</p>
          <p>Main Actor: ${movie.mainActor}</p>
        </div>
      `;
      movieGrid.appendChild(movieCard);
    }
  }
}

// Add event listeners to genre links
document.querySelectorAll(".sidebar a").forEach((link) => {
  link.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent default link behavior
    const genre = this.getAttribute("data-genre");
    updateMovieGrid(genre);
  });
});

// Initialize with a default genre
updateMovieGrid("all");
