PROJECT_ID=desafio-altostratus

gcloud run deploy \
  --image gcr.io/desafio-altostratus/desafio-altostratus-wp-offline \
  --platform managed \
  --allow-unauthenticated
