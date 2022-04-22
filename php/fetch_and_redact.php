<?php

function hash_value($value)
{
    return hash('sha256', $value);
}