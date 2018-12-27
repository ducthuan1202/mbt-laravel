<?php

namespace App\Helpers;


use function GuzzleHttp\Psr7\str;

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

    public static function getDateRangeOfThisWeek(){
        $startTime = strtotime('this week', time());
        $startOfWeek = date('Y-m-d', $startTime);
        $endOfWeek = date('Y-m-d', $startTime + 60*60*24*7);
        return sprintf('%s - %s', $startOfWeek, $endOfWeek);
    }

    public static function getDateRangeOfNextWeek(){
        $startTime = strtotime('next week', time());
        $startOfWeek = date('Y-m-d', $startTime);
        $endOfWeek = date('Y-m-d', strtotime('+6 day', $startTime));
        return sprintf('%s - %s', $startOfWeek, $endOfWeek);
    }

    public static function getDateRangeOfThisMonth(){
        $startOfWeek = date('Y-m-01');
        $endOfWeek = date('Y-m-t', time());
        return sprintf('%s - %s', $startOfWeek, $endOfWeek);
    }
    public static function getDateRangeOfNextMonth(){
        $startOfWeek = date('Y-m-01', strtotime( '+1 month'));
        $endOfWeek = date('Y-m-t', strtotime( '+1 month'));
        return sprintf('%s - %s', $startOfWeek, $endOfWeek);
    }
}
