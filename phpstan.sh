#!/usr/bin/env bash

if [ $# -gt 0 ]
then
level=$1
else
level=3
fi

conf=./phpstan.neon
bin=../../apps/vendor/bin/phpstan
path=./src
output=./tests/_output/phpstan.L$level.txt

#php $bin clear-result-cache
#一般level3, level3 不行，level2 也勉强
php -d memory_limit=1G $bin analyse -c $conf -l $level $path > $output

