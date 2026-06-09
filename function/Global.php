<?php

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

define('ACTION_CREATE', 'getCreate');
define('ACTION_UPDATE', 'getUpdate');
define('ACTION_DELETE', 'getDelete');
define('ACTION_EMPTY', 'empty');
define('ACTION_TABLE', 'getTable');
define('ACTION_PRINT', 'getPrint');
define('ACTION_EXPORT', 'getExport');
define('ERROR_PERMISION', 'This action is unauthorized');
define('TOAST_SUCCESS', 'Data berhasil di proses !');
define('TOAST_FAILED', 'Proses Error !');

function formatDate($value, $datetime = false)
{
    if (empty($value)) {
        return null;
    }

    if ($datetime === false) {
        $format = 'd/m/Y';
    } elseif ($datetime === true) {
        $format = 'd/m/Y H:i:s';
    } else {
        $format = $datetime;
    }

    if ($value instanceof Carbon) {
        $value = $value->format($format);
    } elseif ($value instanceof CarbonImmutable) {
        $value = Carbon::parse($value)->format($format);
    } elseif (is_string($value)) {
        $value = Carbon::parse($value)->format($format);
    }

    return $value ?: null;
}

function formatAngka(int $value, $simbol = null)
{
    return $simbol.number_format($value, 0, ',', '.');
}

function formatLabel($value)
{
    $label = Str::of($value);
    if ($label->contains('_')) {
        $label = $label = $label->explode('_')->last();
    } else {
        $label = $label->replace('[]', '');
    }

    return ucfirst($label);
}

function unic($length)
{
    $chars = array_merge(range('a', 'z'), range('A', 'Z'));
    $length = intval($length) > 0 ? intval($length) : 16;
    $max = count($chars) - 1;
    $str = '';

    while ($length--) {
        shuffle($chars);
        $rand = mt_rand(0, $max);
        $str .= $chars[$rand];
    }

    return strtoupper($str);
}

function module($action = null)
{
    $module = request()->route()->getAction('name');

    if ($action) {
        return $module.'.'.$action;
    }

    return $module;
}

function moduleRoute($action = null, $params = [])
{
    $route = route(module($action), $params);

    return $route;
}

function nominalQRIS($qris_data, $amount) {
    $amountStr = number_format($amount, 2, '.', '');
    $amountLength = strlen($amountStr);
    $amountField = "54" . str_pad($amountLength, 2, '0', STR_PAD_LEFT) . $amountStr;

    // Hilangkan field 54 (nominal) yang lama
    $qris_data = preg_replace('/54\d{2}\d+/', '', $qris_data);

    // Hilangkan CRC lama (tag 63)
    $qris_data = preg_replace('/6304.{4}$/', '', $qris_data);

    $new_qris = $qris_data . $amountField . "6304";
    $crc = strtoupper(dechex(crc16($new_qris)));
    $crc = str_pad($crc, 4, '0', STR_PAD_LEFT);
    return $new_qris . $crc;
}

function crc16($data) {
    $crc = 0xFFFF;
    for ($i = 0; $i < strlen($data); $i++) {
        $crc ^= ord($data[$i]) << 8;
        for ($j = 0; $j < 8; $j++) {
            if ($crc & 0x8000) {
                $crc = ($crc << 1) ^ 0x1021;
            } else {
                $crc = $crc << 1;
            }
            $crc &= 0xFFFF;
        }
    }
    return $crc;
}