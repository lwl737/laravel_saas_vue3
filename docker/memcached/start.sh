#!/bin/sh

memcached  -u root -d -l 0.0.0.0 -m 500 -p 10000

echo "0epbavnfpCR750t3Ms4Tw" | saslpasswd2 -c -a  memcached admin

/bin/bash






