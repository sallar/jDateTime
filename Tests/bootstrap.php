<?php

function loader($class)
{
    $file = dirname(dirname(__FILE__)) .'/'. strtolower($class) . '.class.php';
    if (file_exists($file)) {
        require $file;
    }
}

spl_autoload_register('loader');