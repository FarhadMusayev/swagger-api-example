<?php

function parseErrors($errors): array
{
    $newArr = [];
    foreach($errors as $key => $value) {
        $newArr[] = [
            'field' => $key,
            'reason' => $value[0]
        ];
    }
    return $newArr;
}
