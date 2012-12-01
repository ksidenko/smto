#!/bin/sh
#
if pgrep -c getall_loop1.sh >/dev/null 
 then
  echo getall_loop1.sh is already running
 else
  /etc/pw/bin/getall_loop1.sh &
fi  
#
if pgrep -c showinfo >/dev/null
 then 
  echo showinfo is already running
 else
 /etc/pw/bin/showinfo /etc/pw/config/show_cfg.txt  >/dev/null  2>>/var/pw/log/showerr.txt &
fi 
#
if pgrep -c getcr_loop.sh >/dev/null 
 then
  echo getcr_loop.sh is already running
 else
  /etc/pw/bin/getcr_loop.sh &
fi  
#
