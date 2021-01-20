<?php

namespace App;

class USBEParser
{

    public static function parse(string $htmlResponse)
    {

        $htmlDoc = new \DOMDocument();
        $htmlDoc->loadHtml($htmlResponse);

        $xpath = new \DOMXpath($htmlDoc);

        $elements = $xpath->query("//div[contains(@class, 'tblrow') and not(@id)]");

        $calendarEvents = [];

        foreach ($elements as $row) {
            $event = [];

            $activity       = $xpath->query('.//div[@id="activity"]//p', $row);
            $event['title'] = $activity->item(0)->lastChild->textContent;


            $date          = $xpath->query('.//div[@id="date"]//p', $row);
            $event['date'] = $date->item(0)->lastChild->textContent;

            $time               = $xpath->query('.//div[@id="time"]//p', $row);
            $event['time']      = $time->item(0)->lastChild->textContent;
            $event['startTime'] = explode('-', $event['time'])[0];
            $event['stopTime']  = explode('-', $event['time'])[1];

            $other             = $xpath->query('.//div[@id="other"]//p', $row);
            $event['moreInfo'] = $other->item(0)->textContent;

            //Get nextSiblings of activity for location and teacher
            $locationSel       = $xpath->query('.//div[@id="activity"]//following-sibling::div[1]//p', $row);
            $event['location'] = $locationSel->item(0)->textContent;

            
            $teacherSel       = $xpath->query('.//div[@id="activity"]//following-sibling::div[2]//p', $row);
            $event['teacher'] = $teacherSel->item(0)->lastChild->textContent;


            $calendarEvents[] = $event;
        }

        
        return $calendarEvents;
    }

    public static function getTitle(string $htmlResponse){

        $htmlDoc = new \DOMDocument();
        $htmlDoc->loadHtml($htmlResponse);

        $xpath = new \DOMXpath($htmlDoc);
        $title       = $xpath->query('.//div[@id="modulebanner"]//h1', $row);
        return $title->item(0)->textContent;
    }
}
