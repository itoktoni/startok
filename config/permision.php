<?php

$restrict = [];

$restrict['user']['product'][] = 'save';
$restrict['user']['product'][] = 'delete';
$restrict['admin']['category'] = false;

return $restrict;
