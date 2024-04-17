#!/usr/bin/python

import os
import os.path
import logging
import sys

# Initialize the logging
root = logging.getLogger()
root.setLevel(logging.DEBUG)
ch = logging.StreamHandler(sys.stdout)
ch.setLevel(logging.DEBUG)
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
ch.setFormatter(formatter)
root.addHandler(ch)

php_ini_folder = "/etc/php.d/"
php_ini = "php.ini"

def replace_nginx_config():
    """
   Nginx is a pain to pass ENV variables to PHP so the method I found that works is using fastcgi_param
   This is a heavy duty method, there must be a better way.
   :return:
   """
    environment_variables = "/etc/environment"
    aws_default_region = "us-east-1"
    aws_deploy_environment = "PROD-US"
    with open(environment_variables) as f:
        for line in f:
            s = line.replace("\n", "")
            if "export AWS_DEFAULT_REGION=" in s:
                val1 = s.split("=")
                aws_default_region = val1[1]

    if 'DEPLOYMENT_GROUP_NAME' in os.environ:
        deployment_group_name = os.environ["DEPLOYMENT_GROUP_NAME"]
        # Add here new environments  and the updated connection strings
        if deployment_group_name == "PROD-US":
            aws_deploy_environment = "PROD-US"
        elif deployment_group_name == "Test":
            aws_deploy_environment = "TEST"
        else:
            message = "DEPLOYMENT_GROUP_NAME not set up with the correct value " + deployment_group_name
            logging.error(message)
            raise Exception(message)

    nginx_conf_folder = "/etc/nginx/conf.d/"
    nginx_conf_file = "default.conf"
    nginx_conf = nginx_conf_folder + nginx_conf_file

    if aws_default_region:
        inplace_change(nginx_conf, "%AWS_DEFAULT_REGION%", aws_default_region)
    else:
        raise Exception("Missing AWS_DEFAULT_REGION in " + environment_variables)

    if aws_deploy_environment:
        inplace_change(nginx_conf, "%DEPLOY_ENVIRONMENT%", aws_deploy_environment)
    else:
        raise Exception("Missing DEPLOY_ENVIRONMENT " + environment_variables)

    logging.info("nginx has been updated")


def inplace_change(filename, old_string, new_string):
    # Safely read the input filename using 'with'
    with open(filename) as f:
        s = f.read()
        if old_string not in s:
            logging.info('"{old_string}" not found in {filename}.'.format(**locals()))
            return

    # Safely write the changed content, if found in the file
    with open(filename, 'w') as f:
        print('Changing "{old_string}" to "{new_string}" in {filename}'.format(**locals()))
        s = s.replace(old_string, new_string)
        f.write(s)

def use_php_ini(file_termination):
    """
    this method modifies the php.ini file to add the given setting if not set already
    :param entry:
    :return:
    """
    fname = php_ini_folder + php_ini + "-" + file_termination
    foriginal = php_ini_folder + "10-" + php_ini
    logging.info("fname=" + fname)

    if os.path.isfile(foriginal):
        os.remove(foriginal)
    else:
        logging.warning("fname not found")

    os.rename(fname, foriginal)
    logging.info("fname has been updated")


replace_nginx_config();
