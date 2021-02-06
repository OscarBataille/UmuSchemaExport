# UmuSchemaExport
Collection of tools to scrape calendars from different sources at Umeå Universitet and export them as iCalendar.
Currently supported:
- Online calendar
- Umeå School of Business and Economics  (USBE) schema website
## Online calendar
Scrape Umeå university calendar and export it as an iCalendar.


![Image](/static/Schedule.png)

### Usage 
Modify the calendar configuration in ```src/index.php```.

```console
php index.php [Kurskod] [Instanskod] > ical.ics
```

Example: 
```console
php index.php  2KG052 29030HT20
```


## USBE calendar
You can also scrape USBE calendar and export it as an iCalendar.


![Image](/static/USBE.png)

### Usage

1. Get the ID of the calendar from the URL, or use 
```console
./tools/findUSBESchemaID.sh [Kurskod]
``` 
to find the calendar ID for a specific course.
Eg: ```[http://www.hh.umu.se/usbeweb/fek/schema/schema.php?ID=2366]``` the ID is 2366

2. Run 
```console
php src/usbe.php [ID]
```
Eg:
```console 
php src/usbe.php 2366 > generated/financialAccounting.ics
``` 
to export the calendar with ID 2366 to ```generate/financialAccounting.ics```
3. Import the ics to your calendar.
