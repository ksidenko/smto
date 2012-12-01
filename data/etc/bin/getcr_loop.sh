#!/bin/sh
#
echo 'Delete this file to stop getcr_loop.sh' > /etc/pw/config/loopcr_flag.txt
while [ -s /etc/pw/config/loopcr_flag.txt ]; do
 cp /var/pw/log/crloop_log.txt /var/pw/log/crloop_log_0.txt
 date > /var/pw/log/crloop_log.txt
 for name in /etc/pw/config/*.cfg
 do
 if test -f $name # it is really a file
 then
  echo /etc/pw/bin/getdata /etc/pw/config/allcfg1.txt /etc/pw/config/allcfg2.txt $name "$name"1 /etc/pw/config/allshow.txt >>/var/pw/log/crloop_log.txt
  /etc/pw/bin/getdata /etc/pw/config/allcfg1.txt /etc/pw/config/allcfg2.txt $name "$name"1 /etc/pw/config/allshow.txt >>/var/pw/log/crloop_log.txt 2>>/var/pw/log/crloop_err.txt
  #sleep 1
 fi
 done
 sleep 5
done
#
