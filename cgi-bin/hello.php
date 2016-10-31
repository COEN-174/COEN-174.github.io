<?php
$dir = 'test_dir';

if(!file_exists($dir)){
    $oldmask = umask(0);
    mkdir($dir, 0744);
}
file_put_contents($dir.'/test.txt','RAGING RAGE');
?>
