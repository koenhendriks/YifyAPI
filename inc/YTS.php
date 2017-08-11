<?php

/**
 * Class YTS
 *
 * A simple PHP wrapper for the YTS / Yify api (version 2)
 *
 * @author Koen Hendriks <info@koenhendriks.com>
 * @copyright Copyright (c) 2015, Koen Hendriks
 * @link http://koenhendriks.com
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class YTS {
	
	const BASE_URL = 'https://yts.to';

    /**
     * ListMovies
     * Used to list and search through out all the available movies. Can sort, filter, search and order the results
     *
     * @param int $limit The limit of results per page that has been set
     * @param int $page Used to see the next page of movies, eg limit=15 and page=2 will show you movies 15-30
     * @param string $quality Used to filter by a given quality
     * @param int $minimum_rating Used to filter movie by a given minimum IMDb rating
     * @param int $query_term Used for movie search, matching on: Movie Title/IMDb Code, Actor Name/IMDb Code, Director Name/IMDb Code
     * @param string $genre Used to filter by a given genre (See http://www.imdb.com/genre/ for full list)
     * @param string $sort_by Sorts the results by chosen value
     * @param string $order_by Orders the results by either Ascending or Descending order
     * @param bool $with_rt_ratings Returns the list with the Rotten Tomatoes rating included
     * @return bool| array false if no movies were found, an array with movie objects if their are results
     * @throws Exception thrown when HTTP request or API request fails
     */
    public function listMovies($quality = 'All', $limit = 20, $query_term = 0, $page = 1, $minimum_rating = 0, $genre = 'All', $sort_by = 'date-added', $order_by = 'desc', $with_rt_ratings = false){
        $baseUrl =  self::BASE_URL . '/api/v2/list_movies.json';
        $parameters = '?limit='.$limit.'&page='.$page.'&quality='.$quality.'&minimum_rating='.$minimum_rating.'&query_term='.$query_term.'&genre='.$genre.'&sort_by='.$sort_by.'&order_by='.$order_by.'&with_rt_ratings='.$with_rt_ratings;

        $data = $this->getFromApi($baseUrl.$parameters);

        if($data->movie_count == 0)
            return false;

        return $data->movies;
    }

    /**
     * MovieDetail
     * Returns the information about a specific movie
     *
     * @param int $movie_id
     * @param bool $with_images
     * @param bool $with_cast
     * @return string
     * @throws Exception thrown when HTTP request or API request fails
     */
    public function movieDetail($movie_id, $with_images = false, $with_cast=false){
        $baseUrl = self::BASE_URL . '/api/v2/movie_details.json';
        $parameters = '?movie_id='.$movie_id.'&with_images'.$with_images.'&with_cast='.$with_cast;

        return $this->getFromApi($baseUrl.$parameters);
    }

    /**
     * MovieSuggestions
     * Returns 4 related movies as suggestions for the user
     *
     * @param int $movie_id The ID of the movie
     * @return array array with movie objects
     * @throws Exception thrown when HTTP request or API request fails
     */
    public function movieSuggestions($movie_id){
        $baseUrl = self::BASE_URL . '/api/v2/movie_suggestions.json?movie_id='.$movie_id;

        $data = $this->getFromApi($baseUrl);

        if($data->movie_suggestions_count == 0)
            return false;

        return $data->movie_suggestions;
    }

    /**
     * MovieComments
     * Returns all the comments for the specified movie
     *
     * @param $movie_id
     * @return array array with comments objects
     * @throws Exception thrown when HTTP request or API request fails
     */
    public function movieComments($movie_id){
        $baseUrl = self::BASE_URL . '/api/v2/movie_comments.json?movie_id='.$movie_id;

        $data = $this->getFromApi($baseUrl);

        if($data->comment_count == 0)
            return false;

        return $data->comments;
    }

    /**
     * MovieReviews
     * Returns all the parental guide ratings for the specified movie
     *
     * @param $movie_id
     * @return array array with review objects
     * @throws Exception thrown when HTTP request or API request fails
     */
    public function movieReviews($movie_id){
        $baseUrl = self::BASE_URL . '/api/v2/movie_reviews.json?movie_id='.$movie_id;

        $data = $this->getFromApi($baseUrl);

        if($data->review_count == 0)
            return false;

        return $data->reviews;
    }

    /**
     * MovieParentalGuides
     * Returns all the parental guide ratings for the specified movie
     *
     * @param $movie_id
     * @return array array with parental guide objects
     * @throws Exception thrown when HTTP request or API request fails
     */
    public function movieParentalGuides($movie_id){
        $baseUrl = self::BASE_URL . '/api/v2/movie_parental_guides.json?movie_id='.$movie_id;

        $data = $this->getFromApi($baseUrl);

        if($data->parental_guide_count == 0)
            return false;

        return $data->parental_guides;
    }

    /**
     * List Upcoming
     * Returns the 4 latest upcoming movies
     *
     * @return array array with movie objects
     * @throws Exception thrown when HTTP request or API request fails
     */
    public function listUpcoming(){
        $baseUrl = self::BASE_URL . '/api/v2/list_upcoming.json';

        $data = $this->getFromApi($baseUrl);

        if($data->upcoming_movies_count == 0)
            return false;

        return $data->upcoming_movies;
    }

    /**
     * GetFromApi
     * Does the requests to the yts api
     *
     * @param string $url the url that will be called
     * @return mixed $data object with the data from the API
     * @throws Exception thrown when HTTP request or API request fails
     */
    private function getFromApi($url){
        if (!$data = file_get_contents($url)) {
            $error = error_get_last();

            throw new Exception("HTTP request failed. Error was: " . $error['message']);
        } else {
            $data = json_decode($data);

            if($data->status != 'ok')
                throw new Exception("API request failed. Error was: " . $data->status_message);

            return $data->data;
        }
    }
}