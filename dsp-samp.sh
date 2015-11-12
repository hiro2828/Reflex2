#!/bin/bash
#
# if-samp.sh
# return 1 if samplicate is running. 
#
state=`ps -el |grep samplicate | wc -l`
if [ $state != "0" ] 
then
    echo "there is stream-reflex running."
    exit 1
else
    echo "there is no stream-reflex running."
    rtpdump -F ascii -t 0.017 /6000 
    exit 0
fi
#
