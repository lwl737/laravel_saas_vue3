#!/bin/sh

memcached  -u root -d -l 172.16.1.8 -m 500 -p 10000

echo "0epbavnfpCR750t3Ms4Tw" | saslpasswd2 -c -a  memcached admin

/bin/bash






