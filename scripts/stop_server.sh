#!/bin/bash
#Stop nginx and apache to prevent more requests coming in during update.

if chkconfig --list | grep -Fq 'nginx'; then    
  sudo service nginx stop
fi

if chkconfig --list | grep -Fq 'php-fpm'; then    
  sudo service php-fpm stop
fi

