#!/usr/bin/env bash

#########################
# Initialize
#########################
#sudo yum update -y
#sudo yum install php-mbstring

##############################################
# Remove the health_check if exists in the server
# This is to support multiple projects deployed in the same server.
##############################################
file="/etc/nginx/conf.d/health_check.conf"
if [ -f "$file" ]
then
    sudo rm /etc/nginx/conf.d/health_check.conf
fi

##############################################
# Remove the current crontab if they exist in the target server
# this is now managed by Lambda and runs on it's own stack.
##############################################
file="/var/spool/cron/root"
if [ -f "$file" ]
then
    sudo rm /var/spool/cron/root
fi

##############################################
# Remove the virtual conf if still there, its legacy
##############################################
file="/etc/nginx/conf.d/default.conf"
if [ -f "$file" ]
then
    sudo rm /etc/nginx/conf.d/default.conf
fi

###############################################
#remove the default server from /nginx.conf 
#so we can have a health ping returned by nginx
###############################################
sed -i '/listen       80 default_server;/c\listen       80;' /etc/nginx/nginx.conf
sed -i '/listen       [::]:80 default_server;/c\listen       [::]:80;' /etc/nginx/nginx.conf

###############################################
#Install mysql drivers for Python
#required to run the mysql migrations
###############################################
#if ! command -v pip &> /dev/null
#then
#    curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py
#    python get-pip.py
#fi

#python -m pip install mysql-connector-python 