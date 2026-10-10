#!/bin/sh
chown es -R /elasticsearch-8.15.0
su es -c "/elasticsearch-8.15.0/bin/elasticsearch"
