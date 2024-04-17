#!/usr/bin/python

import os
import os.path
import logging
import sys
import mysql.connector
import pathlib
import boto3

# Initialize the logging
root = logging.getLogger()
root.setLevel(logging.DEBUG)
ch = logging.StreamHandler(sys.stdout)
ch.setLevel(logging.DEBUG)
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
ch.setFormatter(formatter)
root.addHandler(ch)

#db_scripts = "I://projects/customers/captini/ScanOurMenu/scripts/db"
db_scripts = "/scripts/db"


def get_secret():
    secret_name = "ScanOurMenuDBRoot"
    region_name = "eu-west-2"

    # Create a Secrets Manager client
    session = boto3.session.Session()
    client = session.client(
        service_name='secretsmanager',
        region_name=region_name
    )

    # In this sample we only handle the specific exceptions for the 'GetSecretValue' API.
    # See https://docs.aws.amazon.com/secretsmanager/latest/apireference/API_GetSecretValue.html
    # We rethrow the exception by default.

    try:
        get_secret_value_response = client.get_secret_value(
            SecretId=secret_name
        )
    except ClientError as e:
        if e.response['Error']['Code'] == 'DecryptionFailureException':
            # Secrets Manager can't decrypt the protected secret text using the provided KMS key.
            # Deal with the exception here, and/or rethrow at your discretion.
            raise e
        elif e.response['Error']['Code'] == 'InternalServiceErrorException':
            # An error occurred on the server side.
            # Deal with the exception here, and/or rethrow at your discretion.
            raise e
        elif e.response['Error']['Code'] == 'InvalidParameterException':
            # You provided an invalid value for a parameter.
            # Deal with the exception here, and/or rethrow at your discretion.
            raise e
        elif e.response['Error']['Code'] == 'InvalidRequestException':
            # You provided a parameter value that is not valid for the current state of the resource.
            # Deal with the exception here, and/or rethrow at your discretion.
            raise e
        elif e.response['Error']['Code'] == 'ResourceNotFoundException':
            # We can't find the resource that you asked for.
            # Deal with the exception here, and/or rethrow at your discretion.
            raise e
    else:
        # Decrypts secret using the associated KMS CMK.
        # Depending on whether the secret is a string or binary, one of these fields will be populated.
        if 'SecretString' in get_secret_value_response:
            secret = get_secret_value_response['SecretString']
            return secret
        else:
            decoded_binary_secret = base64.b64decode(get_secret_value_response['SecretBinary'])
            return decoded_binary_secret



def get_database_connection():
    user = get_secret()

    if 'DEPLOYMENT_GROUP_NAME' in os.environ:
        deployment_group_name = os.environ["DEPLOYMENT_GROUP_NAME"]
        # Add here new environments  and the updated connection strings
        user = get_secret()
        print(user)

        if deployment_group_name == "Prod_UK":
            return mysql.connector.connect(user='codedeploy',
                                           password='0mn1d4t4',
                                           host='localhost',
                                           port='3306',
                                           database='scanourmenu')
        elif deployment_group_name == "Test":
            return mysql.connector.connect(user='codedeploy',
                                           password='0mn1d4t4',
                                           host='localhost',
                                           port='3306',
                                           database='scanourmenu')
        else:
            message = "DEPLOYMENT_GROUP_NAME not set up with the correct value " + deployment_group_name
            logging.error(message)
            raise Exception(message)
    else:
        return mysql.connector.connect(user='codedeploy',
                                       password='0mn1d4t4',
                                       host='localhost',
                                       port='3306',
                                       database='scanourmenu')


def get_database_version(conn) :
    
    cursor = conn.cursor()
    command = "SELECT max(version)  FROM db_schema_versions"
    try:
        cursor.execute(command)
        row = cursor.fetchone()
    except:
        return 0

    if row is None: return 0
    if row[0] is None: return 0
    
    return row[0]



def get_folders_to_apply(version):
    folders = []
    # r=root, d=directories, f = files
    for r, d, f in os.walk(db_scripts):
        for folder in d:
            if int(folder) > version:
                folders.append(os.path.join(r, folder))
    return folders


def get_scripts_to_apply(folder):
    files = []
    # r=root, d=directories, f = files
    for r, d, f in os.walk(folder):
        for file in f:
            if '.sql' in file:
                files.append(os.path.join(r, file))
    return sorted(files)



def apply_script_to_db(conn, file):
    fd = open(file, 'r')
    sqlFile = fd.read()
    fd.close()
    sqlCommands = sqlFile.split(';')
    cursor = conn.cursor()

    for command in sqlCommands:
        try:
            if command.strip() != '':
                if command.find("DELETE") > -1 or command.find("DROP")>-1:
                    raise Exception("DELETES AND DROPS NOT ALLOWED")
                else:
                    cursor.execute(command)
        except IOError:
            print ("Error Running SQL: " , command)
            raise

 

def update_db():

    conn = get_database_connection()

    #1.- Connect to the DB to get the latest version
    version = get_database_version(conn)

           
    #2.- Read the /scripts/db/ to get all the version folders
    list_folders = get_folders_to_apply(version)


    #3.- For each version that is > than db version apply scripts
    for folder in list_folders:
        #3.1 - Read all files in folder
        list_scripts = get_scripts_to_apply(folder)
        
        #3.2 .- Apply SQL for each file
        for file in list_scripts:
            try:    
                apply_script_to_db(conn,file)
            except: 
                conn.rollback()
                raise

        #3.3 .- Update DB version if all ok
        conn.commit()


update_db()
