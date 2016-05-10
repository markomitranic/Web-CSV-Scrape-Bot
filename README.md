# Web>CSV Scrape Bot

Basic useful feature list:

 * Scrapes a specific page for data by cURL
 * Uses a function to strip data based on before and after pairs.
 * Saves the values to a local CSV file.
 * Echoes the current timestamp and current data values
 * Ready for starting from cli
 * Can be set up easily as an intervaled-cronjob


## Single execute from CLI
You van start the script up directly from command line by issuing `php -f index.php`. This will execute the script once and add one new value to the CSV. Your PHP install must have PHP-(cli) installed. You can issue `php -v` to get this information.

Additionally, you must check the privileges on our files - `chmod 755 index.php`

## Cron Job as a timer
Cron Jobs are a really easy way to handle timed or daemon tasks like this on server. We can set up a Cron, give it our shell script path as a parameter, and set up the timer.
```sh
EDITOR="nano" crontab -e # Open the cronTab in nano editor
*/10 * * * * root /usr/bin/somedirectory/shell # Add the values
```

This Job here will start every 10 minutes. You can read more about setting up a Cron Job from here: [AskUbuntu Q&A](http://askubuntu.com/questions/2368/how-do-i-set-up-a-cron-job/2371#2371)

As with the single execute, if not starting the job as root, you must check the privileges on our files - `chmod 755 index.php`

### Stuff used to make this:

 * [JacobWard PHP scrapeBot](http://www.jacobward.co.uk/web-scraping-with-php-curl-part-1/) - the architecture behind the bot itself
 * [AskUbuntu Q&A](http://askubuntu.com/questions/2368/how-do-i-set-up-a-cron-job/2371#2371) - settng up the Cron Job
 * [LinuxCommand](http://linuxcommand.org/wss0010.php) - writing shell scripts 101
 * Thanks to professor @designbyheart for help with Cron Jobs
