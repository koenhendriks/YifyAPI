<?php

include('inc/YTS.php');
$yts = new YTS();

$movies = $yts->listMovies('All', 3); // All quality, limit 3

// Did we find movies?
if ($movies) {
    foreach ($movies as $movie) {
        echo '<b>' . $movie->title . '</b><br/>';                                                       // Movie title from api
        $torrent = $movie->torrents[0];                                                                 // First torrent
        echo '<a href="' . $torrent->url . '">' . $torrent->url . '</a> (' . $torrent->size . ')<br/>'; // Torrent url and size

        if ($parentalGuides = $yts->movieParentalGuides($movie->id)) {                                  // Did we find parental guides?
            echo 'This movie has:';

            foreach ($parentalGuides as $parentalGuide) {
                echo ' ' . $parentalGuide->type;                                                        // Show parental guides
            }
            echo '<br/>';
        }
        echo '<br/>';
    }
}

/**
 * Example results:
 *
 * Dinosaur Island
 * https://yts.ag/torrent/download/25E4030659954C1D1382FEE2ED37F6F670FB3F97.torrent (693.11 MB)
 *
 * Paris, Texas
 * https://yts.ag/torrent/download/F4747ECDDB1E0EF43A299CB781941D18D67C2F68.torrent (2.07 GB)
 * This movie has: Nudity Violence Profanity Alcohol Frightening
 *
 * R.E.M. by MTV
 * https://yts.ag/torrent/download/1F28D13F40AE91AECC58D649F5F9D84D29321632.torrent (812.23 MB)
 */
