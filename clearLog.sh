#!/bin/bash

# Clear logs for each container
for container_id in $(docker ps -aq)
do
  log_file=$(docker inspect --format='{{.LogPath}}' "$container_id")
  echo "" > $log_file
done
