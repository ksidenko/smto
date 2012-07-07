#!/bin/bash
cd /var/www/smto/

while sleep 10
do
     ./protected/yiic generate index --deltaSec=1 --machineId=2 --countFiles=1 --countLines=10
     ./protected/yiic check
done
exit