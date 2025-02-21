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
  
  // Initial render
  renderWatchlist();