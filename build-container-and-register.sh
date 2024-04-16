#!/bin/bash

echo "This script automates the creation of container and it's registry"

docker build -t gcr.io/desafio-altostratus/desafio-altostratus-wp-offline .

gcloud builds submit --tag gcr.io/desafio-altostratus/desafio-altostratus-wp-offline


exec "$@"
