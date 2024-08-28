#!/usr/bin/env bash

if [ $# -gt 0 ]
then
level=$1
else
level=3
fi

if [ $# -gt 1 ]
then
baseline=" -b dev/phpstan/baseline-L$level.neon"
else
baseline=""
fi

conf=./dev/phpstan/L$level.neon
bin=../../apps/vendor/bin/phpstan
path=./src

#php $bin clear-result-cache
#一般level3, level3 不行，level2 也勉强
php -d memory_limit=1G $bin analyse -c $conf -l $level $path $baseline
