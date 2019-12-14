<?php
$resource = NewMagickWand();
MagickReadImage($resource, 'x.gif');
MagickSetImageFormat($resource, 'gif' );
MagickSetImageCompression($resource,MW_JPEGCompression);
MagickSetImageCompressionQuality($resource,20.0);
header('Content-type: image/gif');
MagickEchoImageBlob($resource);