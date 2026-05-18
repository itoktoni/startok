<?php

$restrict = [];

$restrict['user']['product'][] = 'save';
$restrict['user']['product'][] = 'delete';
$restrict['user']['test'] = null;

return $restrict;
