#!/usr/bin/env bash

if [ $# -gt 0 ]
then
level=$1
else
level=0
fi

conf=./phpstan.neon
bin=../../../../apps/vendor/bin/phpstan
path=../../src
output=../_output/phpstan.L$level.txt

php $bin clear-result-cache
#normally level 3
php -d memory_limit=1G $bin analyse -c $conf -l $level $path > $output
