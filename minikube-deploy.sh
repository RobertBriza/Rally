#!/bin/bash

source ./.env

if [ -z "$MYSQL_HOST" ] || [ -z "$MYSQL_ROOT_PASSWORD" ] || [ -z "$MYSQL_DATABASE" ] || [ -z "$MYSQL_USER" ] || [ -z "$MYSQL_PASSWORD" ]; then
  echo "Error: One or more environment variables are unset. Please ensure all variables are set in .env file."
  exit 1
fi

kubectl create configmap nginx-vhost-config \
--from-file=custom-vhost.conf=deploy/nginx/vhost.conf.template

kubectl create secret generic mysql-secrets \
--from-literal=MYSQL_HOST=$MYSQL_HOST \
--from-literal=MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASSWORD \
--from-literal=MYSQL_DATABASE=$MYSQL_DATABASE \
--from-literal=MYSQL_USER=$MYSQL_USER \
--from-literal=MYSQL_PASSWORD=$MYSQL_PASSWORD

kubectl apply -f deploy/k8s/php-deployment.yaml
kubectl apply -f deploy/k8s/nginx-deployment.yaml
kubectl apply -f deploy/k8s/db-statefulset.yaml

kubectl apply -f deploy/k8s/db-service.yaml
kubectl apply -f deploy/k8s/php-service.yaml
kubectl apply -f deploy/k8s/nginx-service.yaml
