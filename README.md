# PHP CI Standard with Multi-stages

Multi-stage continuous integration for PHP application

## Quickstart

We will setup CI use github action

**Setup repository secets**

1. Open repo -> Settings -> Secrets -> Actions.

2. Add new secrets: 

   - PRIVATE_KEY   (ssh private key of remote user)
   - REMOTE_IP       (the ip address of remote server)

3. Open tab Actions to see the result.