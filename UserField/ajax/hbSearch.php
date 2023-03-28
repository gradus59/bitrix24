<?php

use GraDus59\Bitrix24\Storage\Ajax;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$data = Ajax::getRequest(true);

echo GraDus59\Bitrix24\UserField\HbSearch\SelectList::ajaxItems(
    $data['search'],
    $data['ajaxData']['hbId'],
    $data['ajaxData']['fieldSearch'],
    $data['ajaxData']['limit']
);