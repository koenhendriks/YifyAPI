# Yify API
### A simple PHP wrapper for the YTS / Yify api (version 2)

This PHP class makes use of the YTS / Yify api v2 which can be found on [YTS.ag]. It's extremely easy to setup and use. For more info see the examples below.

## Functions

This project is a work in progress. It only uses basic movie functionality for now. Here is the list of supported functions

* List Movies
* Get Movie Details
* Get Movie Suggestions
* Get Movie Comments
* Get Movie Reviews
* Get Movie Parental Guides
* List Upcoming Movies

## How to use

1. Include the `inc/YTS.php` in your PHP code.
2. Start using the API.

## Examples

### List latest movies with torrents
Showing all latest movies (default limit of 20)

```php
include('inc/YTS.php');
$yts = new YTS();

$movies = $yts->listMovies();

if($movies) {
    foreach($movies as $movie){
        echo $movie->title.'<br/>';
    }
}
```

Here is a simple example of finding the latest 3 movies, getting their torrent links and list the parental guids.

```php
include('inc/YTS.php');
$yts = new YTS();

$movies = $yts->listMovies('All', 3); // All quality, limit 3

if($movies){
    foreach($movies as $movie){
        echo '<b>'.$movie->title.'</b><br/>';
        $torrent = $movie->torrents[0];         // First torrent
        echo '<a href="'.$torrent->url.'">'.$torrent->url.'</a> ('.$torrent->size.')<br/>';

        if($parentalGuides = $yts->movieParentalGuides($movie->id)){
            echo 'This movie has:';

            foreach($parentalGuides as $parentalGuide) {
                echo ' ' . $parentalGuide->type;
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


```

## License

GPL-3.0. See [License.md] for details.

[YTS.ag]: https://yts.ag/api
[License.md]: License.md
