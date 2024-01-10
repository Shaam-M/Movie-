<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Display</title>
    <style>
        #movie-list {
            display: flex;
            gap: 10px;
            flex-direction: row;
            flex-wrap: wrap;

        }

        .movie {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);


            /* ... (previous styles) ... */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;



            /* ... (previous styles) ... */
            margin: 10px;
        }

        .remove-button {
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <!-- Your navigation or header content -->
    </header>

    <main>
        <h1>Movie Display</h1>
        <div id="movie-list">
        </div>
    </main>

    <footer>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            const API_KEY = '1cf50e6248dc270629e802686245c2c8';
            const BASE_URL = 'https://api.themoviedb.org/3';
            const IMG_URL = 'https://image.tmdb.org/t/p/w200';


            // Fetch user-specific movie IDs from the database using AJAX
            $.ajax({
                url: 'php/disfav.php',
                method: 'GET',
                success: function (response) {
                    const movieIds = JSON.parse(response);
                    fetchMovies(movieIds);
                },
                error: function (jqXHR, textStatus, errorThrown) {

                    console.error('Error fetching user-specific movie IDs:', textStatus, errorThrown);
                }
            });

            // Fetch movie details using TMDB API
            function fetchMovies(ids) {
                const movieList = $('#movie-list');

                ids.forEach(function (id) {
                    $.ajax({
                        url: `${BASE_URL}/movie/${id}?api_key=${API_KEY}`,
                        method: 'GET',
                        success: function (movie) {
                            const releaseYear = new Date(movie.release_date).getFullYear();
                            const movieItem = `
                                <div class="movie">
                                    <img src="${IMG_URL}${movie.poster_path}" alt="${movie.title}">
                                    <h2> ${movie.title}</h2 >
                                    <p>Year: ${releaseYear}</p>
                                    <button class="remove-button" data-movie-id="${id}" style="width:70px">Remove</button>
                                </div >
                        `;
                            movieList.append(movieItem);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error(`Error fetching movie with ID ${id}:`, textStatus, errorThrown);
                        }
                    });
                });

                movieList.on('click', '.remove-button', function () {
                    const movieIdToRemove = $(this).data('movie-id');
                    removeMovie(movieIdToRemove);
                    $(this).closest('.movie').remove();
                });
                function removeMovie(movieId) {
                    $.ajax({
                        url: 'php/remove.php',
                        method: 'POST',
                        data: { movie_id: movieId },
                        success: function (response) {
                            console.log('Movie removed successfully:', response);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error removing movie:', textStatus, errorThrown);
                        }
                    });
                }

            }

        });
    </script>
</body>

</html>