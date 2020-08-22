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

foreach ($elements as $key => $element) {
    $date = $element->getAttribute('id');

    //echo $element->ownerDocument->saveHTML($element);

    foreach ($xpath->query("div[contains(@class, 'schemalista')]", $element) as $eventData) {
        $calendarEvent = ["date" => $date];

        // If no events
        if (trim($eventData->nodeValue) == '') {
            $calendarEvents[] = $calendarEvent;

            continue;
        }

        $titles = $xpath->query("//h3", $eventData);

        $calendarEvent["title"] = $titles->item(0)->nodeValue;

        $calendarEvents[] = $calendarEvent;

    }

}

var_dump($calendarEvents);
