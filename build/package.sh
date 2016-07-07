#!/bin/bash

newversion=$(date +"%y.%m.%d.%H%M%S")
sourcefile=~/build/3par
targetpath=~/var/check_mk/packages
targetfile=$targetpath/3par
packageName=3par

echo "============================================="
echo $newversion
echo $sourcefile
echo $targetfile
echo "============================================="

cp $sourcefile $targetpath/
sed -i "s/%VERSION%/$newversion/g" $targetfile

cat $targetfile

echo "============================================="
cmk -vP show $packageName
echo "============================================="

cmk -vP pack $packageName
