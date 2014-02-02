<?php

function autoloader($class)
{
    require __DIR__.'/'.$class.'.php';
}
