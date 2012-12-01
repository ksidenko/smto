#!/bin/sh
#
echo 'Delete this file to stop getall_loop2.sh' > /etc/pw/config/loop2_flag.txt
while [ -s /etc/pw/config/loop2_flag.txt ]; do
 cp /var/pw/log/loop2_log.txt /var/pw/log/loop2_log_0.txt
 date > /var/pw/log/loop2_log.txt
 for name in /etc/pw/config/*.cfg
 do
 if test -f $name # it is really a file
 then
  echo /etc/pw/bin/getdata /etc/pw/config/allcfg1.txt /etc/pw/config/allcfg2.txt $name "$name"1 >>/var/pw/log/loop2_log.txt
  /etc/pw/bin/getdata /etc/pw/config/allcfg1.txt /etc/pw/config/allcfg2.txt $name "$name"1 >>/var/pw/log/loop2_log.txt 2>>/var/pw/log/loop2_err.txt
  #sleep 1
 fi
 done
 sleep 30
done
#
