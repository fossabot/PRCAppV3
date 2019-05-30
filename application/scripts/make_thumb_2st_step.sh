#!/bin/bash
# Parametro 1 - file input, file output
#echo$1, $2
#convert "$1"[0] -thumbnail '120x180>' -background white -alpha remove -gravity center "$2"

convert "$1"[0] -thumbnail '120x180>' -background white -alpha remove -strip -gravity center -quality 80 PNG8:"$2"

if [ $? -eq 0 ]; then
	echo 'OK'
else
	echo 'FAIL '
fi
