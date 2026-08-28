<?php

function siteName(): string
{
    return $_SERVER['HTTP_HOST'];
}