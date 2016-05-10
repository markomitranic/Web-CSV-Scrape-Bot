<?php

$url = "http://www.imvu.com/";
$playersArguments = [
	'<span id="online_count"><b><span class=\'notranslate\'>',
	'</span>'
];
$countriesArguments = [
	'<span id="country_count">in <b><span class=\'notranslate\'>',
	'</span>'
];
date_default_timezone_set("Europe/Belgrade"); 

// Get website
$scraped_website = curl($url); 
if (!$scraped_website) { die('Connection Error'); }

// Scrape only the needed values
$players = scrape_between($scraped_website, $playersArguments[0], $playersArguments[1]);
$countries = scrape_between($scraped_website, $countriesArguments[0], $countriesArguments[1]);

// Parse values as integers and verify that they are integers
$players = intval(str_replace( ',', '', $players ));
$countries = intval($countries);

if (!(is_numeric($players) && is_numeric($countries))) {
	die('The scraped data is not numeric. Stopping everything.');
}

// Create CSV array and prepare it.
$data = [$players, $countries, time()];

// Add record to file.
saveValue($data);

// Print data to screen
echo "There is $data[0] players
From $data[1] countries
Clock: ".date("H:i:s", time())."
";



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
    	if (!file_exists('file.csv')) {
    		die('File NOT found! Stopping scrape.');
    	}
		$fp = fopen('file.csv', 'a');
		fputcsv($fp, $data);
		fclose($fp);
    }