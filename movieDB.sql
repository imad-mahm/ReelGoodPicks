CREATE TABLE movies (
    id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    release_year INT NOT NULL,
    director VARCHAR(100) NOT NULL,
    posterURL VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    trailerURL VARCHAR(255) NOT NULL,
    duration INT NOT NULL,
    `language` VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    box_office DECIMAL(15, 2) CHECK (box_office >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `USER` (
    id INT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    hashedpassword VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    DOB DATE NOT NULL
);

CREATE TABLE reviews (
    id INT PRIMARY KEY,
    movie_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 10),
    review_text TEXT NOT NULL,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id),
    FOREIGN KEY (user_id) REFERENCES USER(id)
);

CREATE TABLE watchlist (
    id INT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USER(id),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

CREATE TABLE user_favorites (
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USER(id),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

CREATE TABLE movie_genres (
    movie_id INT NOT NULL,
    genre VARCHAR(100) NOT NULL,
    PRIMARY KEY (movie_id, genre),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

CREATE TABLE actors (
    id INT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    bio TEXT NOT NULL,
    imageURL VARCHAR(255) NOT NULL
);

CREATE TABLE movie_actors (
    movie_id INT NOT NULL,
    actor_id INT NOT NULL,
    `role` VARCHAR(100) NOT NULL,
    PRIMARY KEY (movie_id, actor_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id),
    FOREIGN KEY (actor_id) REFERENCES actors(id)
);