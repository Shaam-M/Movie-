<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Movies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css" />
    <nav class="menu">

        <div text-align="center" class="head1">MOVIEGUIDE</div>
        <a href=""> </a>
        <div class="menu-log">
            <a href="../disfav.php">FAVORITE</a>
        </div>
    </nav>
    <style>
        form {
            margin: auto;
            width: 50%;
            border: 10px solid black;
            padding: 10px;

        }

        #search-input {
            height: 50px;
            width: 100%;
        }
    </style>

</head>

<body>
    <?php
    session_start();

    if (isset($_POST['movie_id'])) {
        if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
            require "../db_conn1.php";

            $movie_id = $_POST['movie_id'];
            $user_id = $_SESSION['id'];

            $sql = "SELECT * FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":movie_id", $movie_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                $insertSql = "INSERT INTO favorites (user_id, movie_id) VALUES (:user_id, :movie_id)";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $insertStmt->bindParam(":movie_id", $movie_id, PDO::PARAM_INT);

                if ($insertStmt->execute()) {
                    echo "Movie added";
                } else {
                    echo "Error adding movie to favorites: " . $insertStmt->errorInfo()[2];
                }
            } else {
                echo "Movie already added";
            }
        } else {
            echo "User session not found.";
        }
    } else {
        echo "";
    }
    ?>
    <!-- Rest of your HTML and JavaScript code -->

    <header>
        <!-- Your navigation menu here -->
    </header>

    <main class="main">
        <h1>Search and Add Favorite Movies</h1><br />
        <hr />
        <form id="search-form">
            <input type="text" id="search-input" placeholder="Search for a movie"><br />
            <button type="submit">Search</button>

            <div id="movie-details">
                <!-- Display movie details here -->
            </div>

            <button id="add-favorite">Add to Favorites</button>
        </form>

    </main>

    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        const API_KEY = 'api_key=1cf50e6248dc270629e802686245c2c8';
        const BASE_URL = 'https://api.themoviedb.org/3';
        const SEARCH_URL = BASE_URL + '/search/movie?' + API_KEY;

        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const movieDetails = document.getElementById('movie-details');
        const addFavoriteButton = document.getElementById('add-favorite');

        let selectedMovie = null;

        searchForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const searchTerm = searchInput.value;

            fetch(SEARCH_URL + '&query=' + searchTerm)
                .then(response => response.json())
                .then(data => {
                    const movie = data.results[0];
                    if (movie) {
                        selectedMovie = movie;
                        displayMovieDetails(movie);
                    } else {
                        movieDetails.innerHTML = '<p>No results found.</p>';
                        selectedMovie = null;
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });

        addFavoriteButton.addEventListener('click', function () {
            if (selectedMovie) {
                var movie_id = selectedMovie.id;

                $.ajax({
                    url: window.location.href, // Post to the same page URL
                    method: "POST",
                    data: { movie_id: movie_id },
                    success: function (response) {
                        alert("movie added to favorites");
                        // Display the response from the server
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error adding movie to favorites:', textStatus, errorThrown);
                    }
                });
            } else {
                alert('No movie selected.');
            }
        });


        function displayMovieDetails(movie) {
            movieDetails.innerHTML = `
                <h2>${movie.title}</h2>
                <p>${movie.release_date}</p>
                <p>${movie.overview}</p>
                <img src="https://image.tmdb.org/t/p/w200${movie.poster_path}" alt="${movie.title}">
            `;
        }
    </script>
</body>

</html>