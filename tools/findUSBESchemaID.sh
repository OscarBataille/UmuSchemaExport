#!/bin/bash
if [ $# -eq 0 ]; then
    echo "No Course code provided"
    exit 1
fi


for i in {2300..4000}
do
 if curl -f -s  http://www.hh.umu.se/usbeweb/fek/schema/schema.php?ID=$i | grep -iq $1
 then echo $i
fi
done

