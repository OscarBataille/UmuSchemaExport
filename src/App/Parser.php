<?php

namespace App;

class Parser
{

    public static function parse(string $htmlResponse)
    {
        $htmlDoc = new \DOMDocument();
        $htmlDoc->loadHtml($htmlResponse);

        
        $xpath = new \DOMXpath($htmlDoc);

        $elements = $xpath->query("//div[contains(@class, 'veckodagar')]");

        $calendarEvents = [];


        foreach ($elements as $key => $element) {
            $date = $element->getAttribute('id');

            // Get the events
            foreach ($xpath->query(".//div[contains(@class, 'schemahandelse')]", $element) as $eventData) {

                

                // echo $htmlDoc->saveHTML($eventData);

                $calendarEvent = ["date" => $date];

                // If no events, save the day and stop the iteration
                if (trim($eventData->nodeValue) == '') {
                    continue;
                }

                // Extract title
                $titles = $xpath->query(".//h3", $eventData);
                
                $calendarEvent["title"] = $titles->item(0)->nodeValue;

                // Extract Start time
                $spanStartTime = $xpath->query(".//span[contains(@class, 'starttid')]", $eventData);

                $calendarEvent["startTime"] = $spanStartTime->item(0)->nodeValue;

                // Extract stop time
                $spanStopTime = $xpath->query(".//span[contains(@class, 'stopptid')]", $eventData);

                $calendarEvent["stopTime"] = $spanStopTime->item(0)->nodeValue;

                // Extract event time
                $detail = $xpath->query(".//span[@class='eventtyp']/span[contains(@class, 'detaljer')]", $eventData);

                $calendarEvent["eventType"] = trim($detail->item(0)->nodeValue);

                // Extract location time
                $location = $xpath->query(".//span[@class='lokal']/span[contains(@class, 'detaljer')]/a", $eventData);

                $calendarEvent["location"] = trim($location->item(0)->nodeValue);

                // Extract event info
                foreach ($xpath->query(".//div[@class='visamerinfo']", $eventData) as $moreInfo) {

                    $spanMoreInfo = $xpath->query(".//span", $moreInfo);

                    $calendarEvent["moreInfo"][] = trim($spanMoreInfo->item(0)->nodeValue) . " " . trim($spanMoreInfo->item(1)->nodeValue);
                }

                // Add the event to the calendar
                $calendarEvents[] = $calendarEvent;

            }

        }

        return $calendarEvents;
    }
}
