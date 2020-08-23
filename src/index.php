<?php

ini_set('max_execution_time', 0);

// Autoload
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7\Request;

$client = new GuzzleHttp\Client();

$response = $client->request('POST', "https://www.umu.se/utbildning/api/Schema/Index", [

    'form_params' => [
        "btn"                            => "v+",
        "Kurskoder"                      => "2KG052",
        "Instanskoder"                   => "29030HT20",
        "Year"                           => "2020",
        "Month"                          => "9",
        "Day"                            => "3",
        "Lang"                           => "en",
        "ValdDag"                        => "-1",
        "PreviewPage"                    => "",
        "Manadsvy"                       => "False",
        "Kurser[0].Namn"                 => "Sveriges sociala geografi, 7.5 hp",
        "Kurser[0].Instanskod"           => "29030HT20",
        "Kurser[0].FargClass"            => "farg_0",
        "Kurser[0].AlternativSchemalank" => "",
        "Kurser[0].HarPubliceratSchema"  => "True",
        "Kurser[0].AntalValdaGrupper"    => "0",
        "Kurser[0].Vald"                 => "true",
        "X-Requested-With"               => "XMLHttpRequest",
    ],
]);

$body = (string) $response->getBody();

$htmlDoc = new DOMDocument();
$htmlDoc->loadHtml($body);

// echo $htmlDoc->saveHTML();
$xpath = new DOMXpath($htmlDoc);

$elements = $xpath->query("//div[contains(@class, 'veckodagar')]");

$calendarEvents = [];

//Loop through days element
foreach ($elements as $key => $element) {
    $date = $element->getAttribute('id');

    // Get the events
    foreach ($xpath->query("div[contains(@class, 'schemalista')]", $element) as $eventData) {
        $calendarEvent = ["date" => $date];

        // If no events, save the day and stop the iteration
        if (trim($eventData->nodeValue) == '') {
            $calendarEvents[] = $calendarEvent;
            continue;
        }

        // Extract title
        $titles = $xpath->query("//h3", $eventData);

        $calendarEvent["title"] = $titles->item(0)->nodeValue;

        // Extract Start time
        $spanStartTime = $xpath->query("//span[contains(@class, 'starttid')]", $eventData);

        $calendarEvent["startTime"] = $spanStartTime->item(0)->nodeValue;

        // Extract stop time
        $spanStopTime = $xpath->query("//span[contains(@class, 'stopptid')]", $eventData);

        $calendarEvent["stopTime"] = $spanStopTime->item(0)->nodeValue;


        echo $htmlDoc->saveHTML($eventData);

        // Add the event to the calendar
        $calendarEvents[] = $calendarEvent;

    }

}

var_dump($calendarEvents);
