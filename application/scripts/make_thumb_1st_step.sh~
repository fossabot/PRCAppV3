#!/bin/bash
# Parametro 1 - file input, file output
#echo$1, $2
unoconv  -f pdf -o "$2" "$1"

if [ $? -eq 0 ]; then
	echo 'OK'
else
	echo 'FAIL '
fi
