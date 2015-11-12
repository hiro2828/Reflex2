#!/bin/bash
#
# samp.sh
# samplicate incoming ip stream to other destinations
#
pidfile=6000.pid
if [ -f $pidfile ];
then
   kill -9 `cat $pidfile`
   rm -f $pidfile
   exit 1
else
   echo "Stream-Reflex is not running. <br />"
   exit 0
fi
#
