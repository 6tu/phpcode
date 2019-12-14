
export path2web2pici=$1
export path2CACHE=$2
export imgnr=$3

export url=$4
export $xvfb = $5

$xvfb $path2web2pici --url=$url --out=$path2CACHE/$imgnr.png > $path2CACHE/error_.txt 2>&1 &

chmod 664 $path2CACHE/$imgnr.png



