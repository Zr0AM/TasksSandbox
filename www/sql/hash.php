<?php
namespace sql;

function getHash($value)
{
    return password_hash($value, PASSWORD_DEFAULT);
}
