<?php

ini_set('max_execution_time', 0);

// Autoload
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7\Request;

if (!isset($argv[1])) {
    die("You need to specifiy the USBE Schema ID. Eg: For http://www.hh.umu.se/usbeweb/fek/schema/schema.php?ID=2366 the ID is 2366");
}

$client = new GuzzleHttp\Client();

$response = $client->request('GET',
    'http://www.hh.umu.se/usbeweb/fek/schema/schema.php',
    ['query' => [
        'ID' => $argv[1],
        ],
    ]);

$body = (string) $response->getBody();

$calendarEvents = App\USBEParser::parse($body);


// Ical generation
$vCalendar = new \Eluceo\iCal\Component\Calendar(App\USBEParser::getTitle($body));

foreach ($calendarEvents as $event) {

    $vEvent = new \Eluceo\iCal\Component\Event();

    $start     = new \DateTime($event["date"]);
    $timeStart = explode(':', $event["startTime"]);
    $start->setTime((int) $timeStart[0], (int) $timeStart[1]);

    $stop     = new \DateTime($event["date"]);
    $timeStop = explode(':', $event["stopTime"]);
    $stop->setTime((int)$timeStop[0], (int) $timeStop[1]);

    $description = $event['moreInfo'] . " " . $event["location"] . " ". $event['teacher'] . PHP_EOL; 

    
    $vEvent
        ->setDtStart($start)
        ->setDtEnd($stop)
        ->setSummary($event["title"])
        ->setDescription($description);


    $vCalendar->addComponent($vEvent);
}

echo $vCalendar->render();