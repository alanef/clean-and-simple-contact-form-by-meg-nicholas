#!/bin/bash

# Start Mailpit for local email testing
echo "Starting Mailpit..."

docker compose -f docker-compose.mailpit.yml up -d

echo ""
echo "Mailpit Web UI: http://localhost:8510"
echo "SMTP Server: localhost:1510"
echo ""
echo "To stop: docker compose -f docker-compose.mailpit.yml down"