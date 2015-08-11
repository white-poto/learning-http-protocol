<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/11
 * Time: 10:12
 *
 * phpÊµÏÖtrunk±àÂë
 */

header("Transfer-encoding: chunked");
@apache_setenv('no-gzip', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
for ($i = 0; $i < ob_get_level(); $i++)  ob_end_flush();
ob_implicit_flush(1); flush();

function dump_chunk($chunk)
{
    printf("%x\r\n%s\r\n", strlen($chunk), $chunk);
    ob_flush();
    flush();
}

for (;;) {
    $output = array();
    exec("/usr/games/fortune", $output);
    dump_chunk(implode("\n", $output));
    usleep(pow(2,18));
}
