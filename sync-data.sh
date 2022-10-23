#!/bin/bash

COUNT_STATUS=$(kubectl get pods -n magicak-web | grep php- | awk '{ print $3; }' | wc -l)
STATUS=$(kubectl get pods -n magicak-web | grep php- | awk '{ print $3; }')

while [ "$COUNT_STATUS" -ne "1" ] & [ "$STATUS" != "Running" ]; do
    echo "Waiting for pods to be ready..."
    sleep 5
    COUNT_STATUS=$(kubectl get pods -n magicak-web | grep php- | awk '{ print $3; }' | wc -l)
    STATUS=$(kubectl get pods -n magicak-web | grep php- | awk '{ print $3; }')
done

POD_NAME=$(kubectl get pod -n magicak-web | grep php- | awk '{ print $1; }')

kubectl exec $POD_NAME -- mkdir -p /var/www/html/storage/app/public && tar cf - -C /tmp/data-magicak . | kubectl exec -i $POD_NAME -- tar xf - -C /app/html/storage/app/public
