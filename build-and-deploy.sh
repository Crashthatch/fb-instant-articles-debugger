#!/usr/bin/env bash

# Shortcut for easy deployment by Tom. If you're not Tom, you probably shouldn't run this script.

aws ecr get-login --region us-east-1 | bash
aws ecr get-login --region us-east-1 | sed s/docker/hyper/ | bash

docker build -t fbia .
docker tag fbia:latest 016279857314.dkr.ecr.us-east-1.amazonaws.com/fbia:latest
docker push 016279857314.dkr.ecr.us-east-1.amazonaws.com/fbia:latest

hyper pull 016279857314.dkr.ecr.us-east-1.amazonaws.com/fbia:latest
hyper stop fbia
hyper rm fbia
hyper run --size s2 -d --name fbia --env VIRTUAL_HOST=fb-instant-articles-debugger.crashthatch.com -p 80:80 016279857314.dkr.ecr.us-east-1.amazonaws.com/fbia:latest

# Restarts the load balancer in front of all my websites:
../hyper-load-balancer-lb.sh
