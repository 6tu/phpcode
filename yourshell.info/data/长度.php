preg_match('/Content-Length:(.*)/si',$tmp,$arr)){
  return trim($arr[1]);
preg_match('/Content-Length: (\d+)/i',$output,$arr);