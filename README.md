# Johnny the Deployer DEMO

This repository serves as a demonstration on how to build a DevOps chatbot with minimal tools.

The desired functionality is detailed in the diagram below:

![Johnny Demo Workflow](/_docs/entire_workflow.jpg?raw=true)

## Prerequisites

The present codebase assumes that you have Docker installed and started on your local machine, 
and that you are familiar with [`docker-compose`](https://docs.docker.com/compose/). 

Additionally, you will need an Atlassian account for the Jira trial license. 
If you don't have one yet, you can [sign up here](https://id.atlassian.com/signup).

In order to expose your local development environment to Slack you will need the awesome ngrok.
Here's a handy [download link](https://ngrok.com/download).

A little bit of patience :-) as the initial setup gives you enough time for a large coffee.

## Setup

Start the containers by running:
```bash
$ ./bootstrap.sh
```

The process is going to take a while, as it has to build quite a few containers.
The longest setup comes from the Jira one, so please be patient until everything 
is downloaded.

## The components

### External: Jenkins

It is accessible at [http://localhost:3535](http://localhost:3535) 
with user `admin` and password `adminpass`.

### External: Jira

Accessible at [http://localhost:4646](http://localhost:4646).

Once they have been built and started, we need to perform the Jira evaluation license setup.
Here is a [handy video tutorial](https://www.youtube.com/watch?v=v5tqDlQcVss) on how that can be achieved, 
and it also shows how to modify the default workflow for the purpose of this demo.
Please note that this uses the Atlassian account that we marked necessary in the prerequisites section above.

### Johnny: The Bot

A simple Botmaster instance which receives messages from a conversational interface 
(for our demo purposes Slack) and hands them over to "the Brains" to figure out what 
was meant and perform any related work.

### Johnny: The Brains

A PHP backend that communicates with the various external endpoints:
* Watson Conversation
* Jenkins
* Jira

Once the containers are up, finalise the "brains" setup by running:
```bash
$ docker-compose exec brains-php-fpm /var/www/johnny/the-brains/bin/setup_brains.sh
```

You should only perform this once. The above command will install 3rd party dependencies 
and warm up the code cache for you.

For XDebug purposes (TODO add PHPStorm instructions as well):
```bash
# source: https://gist.github.com/chadrien/c90927ec2d160ffea9c4
$ sudo ifconfig en0 alias 10.254.254.254 255.255.255.0
```

## Docker handy commands

Watch all the logs from the running containers:
```bash
$ docker-compose logs -f
```

```bash
# stop all containers
$ docker stop $(docker ps -a -q)

# Delete all containers
docker rm $(docker ps -a -q)

# Delete all images
docker rmi $(docker images -q)
```
