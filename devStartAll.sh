working_directory=$(pwd);
dockerComposeFile="$working_directory/docker-compose.yml"
if [ ! -f $dockerComposeFile ]; then
  cp "$working_directory/docker-compose-dev.yml" $dockerComposeFile;
fi

docker-compose up -d
