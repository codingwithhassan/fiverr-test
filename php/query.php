<?php

function query($field, $value, $exact = TRUE)
{
    // TODO
}

function report()
{
    // TODO
}

query('id', '5be5884a7ab109472363c6cd');
query('id', '5be5884a331b2c695', FALSE);
query('id', '5be5884a331b24639s3cc695');
query('age', '22');
query('age', '20');
query('about', 'exa', FALSE);
query('about', 'ace', FALSE);
query('email', 'mcconnellbranch@zytrek.com');
query('email', 'ryansand@xandem.com');
query('email', 'edwinachang', FALSE);

report();