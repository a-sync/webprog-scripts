<?php
$_ref = array_shift(explode('?', end(explode('/', @$_SERVER['HTTP_REFERER']))));

//echo '00'; // DEBUG

$pads[0] = 0;
$pads[1] = 'text_pad1.txt';
$pads[2] = 'text_pad2.txt';
$pads[3] = 'text_pad3.txt';

if(!function_exists('file_put_contents')) {
  define('FILE_APPEND', 1);

  function file_put_contents($file, $data, $flag = false) {
    $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
    $handle = @fopen($file, $mode);
    $written_bytes = 0;

    if($handle) {
      if(is_array($data)) $data = implode($data);
      
      $written_bytes = fwrite($handle, $data);
      fclose($handle);
      
      return $written_bytes;
    }
    else {
      return false;
    }
  }
}

if($_GET['type'] == 10) {
  echo '1';
  
  $file = $pads[$_POST['n']];
  
  if($file == '') {
    echo '0';
    exit;
  }
  else {
    echo '1';
    @file_put_contents($file, rawurldecode($_POST['value']));
  }
}
elseif($_GET['type'] == 20) {
  echo '2';
  
  $file = $pads[$_POST['n']];
  
  if($file == '') {
    echo '0';
    exit;
  }
  else {
    echo '1';
    echo $_POST['n'].':';
    echo date('Y.m.d. H&#58;i&#58;s', filemtime($file)).':';
    echo @file_get_contents($file);
  }
}

?>