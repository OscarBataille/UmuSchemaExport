<?php

ini_set('max_execution_time', 0);

// Autoload
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7\Request;

if(!isset($argv[1])){
    die("You need to specifiy the Kurskod");
}
if(!isset($argv[2])){
    die("You need to specifiy the Instanskod");
}
$client = new GuzzleHttp\Client();

$currentDate    = strtotime("last Monday - 1 day");
$calendarEvents = [];
$endDate        = strtotime("+1 year");

while ($currentDate < $endDate) {
    $dayPreviousWeek   = (string) date("d", $currentDate);
    $monthPreviousWeek = (string) date("m", $currentDate);
    $yearPreviousWeek  = (string) date("Y", $currentDate);

    $response = $client->request('POST', "https://www.umu.se/utbildning/api/Schema/Index", [

        'form_params' => [
            "btn"                            => "v+",
            "Kurskoder"                      => $argv[1],
            "Instanskoder"                   => $argv[2],
            "Year"                           => $yearPreviousWeek,
            "Month"                          => $monthPreviousWeek,
            "Day"                            => $dayPreviousWeek,
            "Lang"                           => "en",
            "ValdDag"                        => "-1",
            "PreviewPage"                    => "",
            "Manadsvy"                       => "False",
            "Kurser[0].Instanskod"           => $argv[2],
            // "Kurser[0].Namn"                 => "Sveriges sociala geografi, 7.5 hp",
            // "Kurser[0].FargClass"            => "farg_0",
            // "Kurser[0].AlternativSchemalank" => "",
            // "Kurser[0].HarPubliceratSchema"  => "True",
            // "Kurser[0].AntalValdaGrupper"    => "0",
            "Kurser[0].Vald"                 => "true",
            "X-Requested-With"               => "XMLHttpRequest",
        ],
    ]);

    $body = (string) $response->getBody();

    $calendarEvents = array_merge($calendarEvents, App\Parser::parse($body));

    $currentDate = strtotime("+1 week", $currentDate);
}


// var_dump($calendarEvents);

// Ical generation
$vCalendar = new \Eluceo\iCal\Component\Calendar('SverigesSocialaGeografi');

foreach ($calendarEvents as $event) {

    $vEvent = new \Eluceo\iCal\Component\Event();

    $start     = new \DateTime($event["date"]);
    $timeStart = explode('.', $event["startTime"]);
    $start->setTime($timeStart[0], $timeStart[1]);

    $stop     = new \DateTime($event["date"]);
    $timeStop = explode('.', $event["stopTime"]);
    $stop->setTime($timeStop[0], $timeStop[1]);

    $description = $event['eventType'] . " " . $event["location"] . PHP_EOL; 

    foreach ($event["moreInfo"] as  $moreInfo) {
        $description .= $moreInfo .PHP_EOL;
    }
    $vEvent
        ->setDtStart($start)
        ->setDtEnd($stop)
        ->setSummary($event["title"])
        ->setDescription($description);


    $vCalendar->addComponent($vEvent);
}

echo $vCalendar->render();
