<?php

function trimBothSlashes($string)
{
    return ltrim(rtrim($string, '/'), '/');
}