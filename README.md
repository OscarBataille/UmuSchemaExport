# UmuSchemaExport

Scrape Umeå university calendar and export it as an iCalendar.


![Image](/static/Schedule.png)

# Usage 
Modify the calendar configuration in src/index.php.

```php index.php [Kurskod] [Instanskod] > ical.ics```

Example: 
```php index.php  2KG052 29030HT20```


# USBE calendar
You can also scrape USBE calendar and export it as an iCalendar.

![Image](/static/USBE.png)

## Steps
1. Get the ID of the calendar from the URL
Eg: ```http://www.hh.umu.se/usbeweb/fek/schema/schema.php?ID=2366``` the ID is 2366
