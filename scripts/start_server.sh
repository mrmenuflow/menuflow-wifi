#!/bin/bash
#Stop nginx and apache to prevent more requests coming in during update.

sudo service nginx restart
sudo service php-fpm restart

