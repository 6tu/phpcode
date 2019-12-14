export HOME=$6
export path2web2pici=$1
export path2CACHE=$2
export imgnr=$3
export DISPLAY=$5
export url=$4

$path2web2pici $url $path2CACHE/$imgnr > $path2CACHE/error_.txt 2>&1 &

chmod 664 $path2CACHE/$imgnr.png


