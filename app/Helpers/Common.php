<?php

namespace App\Helpers;


class Common
{
    const UNKNOWN_TEXT = 'không xác định';

    public static function generateUniqueCode($mask = '0000', $number = 0)
    {
        if (!is_numeric($number) || $number < 1) return '';
        return substr($mask, 0, -strlen((string)$number)) . $number;
    }

    public static function extractDate($str = '', $separator = ' - ')
    {
        if(empty($str)) return [];
        return explode($separator, $str);
    }

    public static function formatDate($date)
    {
        if (empty($date)) {
            return '';
        }
        return date('d/m/Y', strtotime($date));
    }

    public static function formatMoney($money, $suffix = ' VNĐ')
    {
        if(!empty($money)){
            return number_format((int)$money * 1000) . $suffix;
        }
        return '0'.$suffix;
    }

    public static function dmY2Ymd($date)
    {
        if (preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $date)) {
            $date = str_replace('/', '-', $date);
            return date('Y-m-d', strtotime($date));
        }
        return $date;
    }

    public static function formatNumber($number){
        return number_format($number);
    }
}
