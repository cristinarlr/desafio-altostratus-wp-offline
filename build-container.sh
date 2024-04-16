PROJECT_ID=desafio-altostratus

gcloud builds \
  -t gcr.io/desafio-altostratus/desafio-altostratus-wp-offline . 

gcloud builds submit \ 
  --tag gcr.io/desafio-altostratus/desafio-altostratus-wp-offline
