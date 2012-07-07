#!/bin/bash
cd /var/www/smto/

while sleep 10
do
     ./protected/yiic generate index --deltaSec=1 --machineId=2 --countFiles=1 --countLines=10
     #./protected/yiic generate index --deltaSec=1 --machineId=3 --countFiles=1 --countLines=10
     #./protected/yiic generate index --deltaSec=1 --machineId=4 --countFiles=1 --countLines=10
     #./protected/yiic generate index --deltaSec=1 --machineId=5 --countFiles=1 --countLines=10
     #./protected/yiic generate index --deltaSec=1 --machineId=6 --countFiles=1 --countLines=10
     #./protected/yiic generate index --deltaSec=1 --machineId=7 --countFiles=1 --countLines=10
     #./protected/yiic generate index --deltaSec=1 --machineId=8 --countFiles=1 --countLines=10
     #./protected/yiic generate index --deltaSec=1 --machineId=9 --countFiles=1 --countLines=10

     ./protected/yiic check
     #./protected/yiic check
     #./protected/yiic check
     #./protected/yiic check
     #./protected/yiic check
     #./protected/yiic check
     #./protected/yiic check
     #./protected/yiic check
     #./protected/yiic check
done
exit