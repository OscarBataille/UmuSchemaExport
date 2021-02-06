# UmuSchemaExport
Collection of tools to scrape calendars from different sources at Umeå Universitet and export them as iCalendar.
Currently supported:
- Online calendar
- Umeå School of Business and Economics  (USBE) schema website
## Online calendar
Scrape Umeå university calendar and export it as an iCalendar.


![Image](/static/Schedule.png)

### Usage 
1. Modify the calendar configuration in ```src/index.php```.
2. Execute the tool:
```console
oscar@computer:~/UmuSchemaExport$ php src/index.php [Kurskod] [Instanskod] > ical.ics
```

Example: 
```console
oscar@computer:~/UmuSchemaExport$ php src/index.php  2KG052 29030HT20
```


## USBE calendar
You can also scrape USBE calendar and export it as an iCalendar.


![Image](/static/USBE.png)

### Usage

1. Get the ID of the calendar.
Either from the URL:
Eg: ```[http://www.hh.umu.se/usbeweb/fek/schema/schema.php?ID=2366]``` the ID is 2366.

 Or from the Course Code/Kurskod
```console
oscar@computer:~/UmuSchemaExport$ ./tools/findUSBESchemaID.sh [Kurskod]
``` 
to find the calendar ID for a specific course.
Eg:
```console
oscar@computer:~/UmuSchemaExport$ ./tools/findUSBESchemaID.sh 2FE096
2366
``` 

2. Scrape and convert the data to an iCalendar file. The file is outputed to stdout. 
```console
php src/usbe.php [ID]
```
Eg:
```console 
oscar@computer:~/UmuSchemaExport$ php src/usbe.php 2366 > generated/financialAccounting.ics
``` 
to export the calendar with ID 2366 to ```generate/financialAccounting.ics```
3. Import the ics to your calendar.
