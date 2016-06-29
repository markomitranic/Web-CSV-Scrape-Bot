<?php

$url = "https://www.facebook.com/IMVU/";
$likesArguments = [
    '<span id="PagesLikesCountDOMID"><span class="_52id _50f5 _50f7">',
    ' <span class='
];
$lastPostArguments = [
    '<a class="_5pcq" href="/IMVU/posts/',
    '" target="">'
];
date_default_timezone_set("Europe/Belgrade"); 

// Get website
$scraped_website = curl($url); 
if (!$scraped_website) { die('Connection Error'); }

// Scrape only the needed values
$likes = scrape_between($scraped_website, $likesArguments[0], $likesArguments[1]);
$lastPost = scrape_between($scraped_website, $lastPostArguments[0], $lastPostArguments[1]);


// Parse values as integers and verify that they are integers
$likes = intval(str_replace( '.', '', $likes ));
$lastPost = intval($lastPost);


if (!(is_numeric($likes) && is_numeric($lastPost))) {
    die('The scraped data is not numeric. Stopping everything.');
}

// Create CSV array and prepare it.
$data = [$likes, $lastPost, time()];

// Add record to file.
saveValue($data);

// Print data to screen
echo "Page has $data[0] likes
Last post is $data[1]
Clock: ".date("H:i:s", time())."
";

exit();

    // Defining the basic cURL function
    function curl($url) {
        $ch = curl_init();  // Initialising cURL
        curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); // We have to fake user agent in order for FB to even answer to us...
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
    }

    // Defining the basic scraping function
    function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }

    function saveValue($data) {
        $cwd = dirname(__FILE__)."/file.csv";
        $fp = fopen($cwd, 'a');
        fputcsv($fp, $data);
        fclose($fp);
    }