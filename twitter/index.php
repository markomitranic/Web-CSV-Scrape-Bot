<?php

$url = "https://twitter.com/IMVU";
$scrapeArgs = [
    '<input type="hidden" id="init-data" class="json-data" value="',
    '">'
];





date_default_timezone_set("Europe/Belgrade"); 

// Get website
$scraped_website = curl($url); 
if (!$scraped_website) { die('Connection Error'); }

// Scrape only the needed values
$object = scrape_between($scraped_website, $scrapeArgs[0], $scrapeArgs[1]);
$object = json_decode(html_entity_decode($object))->profile_user;

// Gather the needed data
$followers = $object->followers_count;
$tweets = $object->statuses_count;

// Parse values as integers and verify that they are integers
if (!(is_numeric($followers) && is_numeric($tweets))) {
    die('The scraped data is not numeric. Stopping everything.');
}

// Create CSV array and prepare it.
$data = [$followers, $tweets, time()];

// Add record to file.
saveValue($data);

// Print data to screen
echo "There is $data[0] followers
and $data[1] tweets so far.
Clock: ".date("H:i:s", time())."
";

exit();

    // Defining the basic cURL function
    function curl($url) {
        $ch = curl_init();  // Initialising cURL
        curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
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
        if (!file_exists($cwd)) {
            die('File NOT found! Stopping scrape.');
        }
        $fp = fopen($cwd, 'a');
        fputcsv($fp, $data);
        fclose($fp);
    }