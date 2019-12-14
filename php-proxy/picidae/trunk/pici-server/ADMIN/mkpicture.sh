#!/bin/sh

export DYLD_FRAMEWORK_PATH=$5.app/Contents/Resources/
export PYTHONPATH=/Library/Frameworks/Python.framework/Versions/2.4/lib/python2.4/site-packages/PyObjC
$4 $3/ADMIN/picidae.py -F -o $6/$2 $1
chmod 664 $6/$2.png

