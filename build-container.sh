#!/bin/bash

PROJECT_ID="desafio-altostratus"

# Construir y enviar la imagen del contenedor a Container Registry
gcloud builds submit \
	  --tag gcr.io/$PROJECT_ID/desafio-altostratus-wp-offline \
	    .

# Registrar la imagen en Artifact Registry
gcloud artifacts docker import \
	  gcr.io/$PROJECT_ID/desafio-altostratus-wp-offline \
	    --location=us \
	      --repository=desafio-altostratus-wp-offline


