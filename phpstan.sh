#!/usr/bin/env bash

if [ $# -gt 0 ]
then
level=$1
else
level=0
fi

phpStanBin=../../apps/vendor/bin/phpstan
phpStanConf=./phpstan.neon

php $phpStanBin clear-result-cache
#normally max level 5
php -d memory_limit=1G $phpStanBin analyse -c $phpStanConf -l $level ./src > phpstan.result.$level.txt
