<?php

ini_set('max_execution_time', 0);

// Autoload
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7\Request;

$client = new GuzzleHttp\Client();

$currentDate = strtotime("last Monday - 1 day");
$calendarEvents = [];
$endDate = strtotime("2020-12-25");

while ($currentDate < $endDate) {
    $dayPreviousWeek   = (string) date("d", $currentDate);
    $monthPreviousWeek = (string) date("m", $currentDate);
    $yearPreviousWeek  = (string) date("Y", $currentDate);

    $response = $client->request('POST', "https://www.umu.se/utbildning/api/Schema/Index", [

        'form_params' => [
            "btn"                            => "v+",
            "Kurskoder"                      => "2KG052",
            "Instanskoder"                   => "29030HT20",
            "Year"                           => $yearPreviousWeek,
            "Month"                          => $monthPreviousWeek,
            "Day"                            => $dayPreviousWeek,
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

    $calendarEvents = array_merge($calendarEvents, App\Parser::parse($body));

    $currentDate = strtotime("+1 week", $currentDate);
}
var_dump($calendarEvents);
