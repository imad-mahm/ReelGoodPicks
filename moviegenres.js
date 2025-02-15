const movies = {
  action: [
    {
      title: "Movie Title 1",
      genre: "Action, Thriller",
      image: "https://via.placeholder.com/200x300",
    },
    {
      title: "Movie Title 4",
      genre: "Action, Adventure",
      image: "https://via.placeholder.com/200x300",
    },
  ],
  comedy: [
    {
      title: "Movie Title 2",
      genre: "Comedy, Drama",
      image: "https://via.placeholder.com/200x300",
    },
  ],
  drama: [
    {
      title: "Movie Title 5",
      genre: "Drama, Romance",
      image: "https://via.placeholder.com/200x300",
    },
  ],
  horror: [
    {
      title: "Movie Title 3",
      genre: "Horror, Sci-Fi",
      image: "https://via.placeholder.com/200x300",
    },
  ],
  "sci-fi": [
    {
      title: "Movie Title 6",
      genre: "Sci-Fi, Thriller",
      image: "https://via.placeholder.com/200x300",
    },
  ],
  thriller: [
    {
      title: "Movie Title 1",
      genre: "Action, Thriller",
      image: "https://via.placeholder.com/200x300",
    },
    {
      title: "Movie Title 6",
      genre: "Sci-Fi, Thriller",
      image: "https://via.placeholder.com/200x300",
    },
  ],
};

// Function to update the movie grid based on the selected genre
function updateMovieGrid(genre) {
  const movieGrid = document.getElementById("movie-grid");
  movieGrid.innerHTML = ""; // Clear current movies
  if (genre === "all") {
    // Display all movies
    Object.keys(movies).forEach((genre) => {
      movies[genre].forEach((movie) => {
        const movieCard = document.createElement("div");
        movieCard.className = "movie-card";
        movieCard.innerHTML = `
            <img src="${movie.image}" alt="${movie.title}" />
            <h4>${movie.title}</h4>
            <p>${movie.genre}</p>
          `;
        movieGrid.appendChild(movieCard);
      });
    });
    return;
  }
  movies[genre].forEach((movie) => {
    const movieCard = document.createElement("div");
    movieCard.className = "movie-card";
    movieCard.innerHTML = `
        <img src="${movie.image}" alt="${movie.title}" />
        <h4>${movie.title}</h4>
        <p>${movie.genre}</p>
      `;
    movieGrid.appendChild(movieCard);
  });
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
