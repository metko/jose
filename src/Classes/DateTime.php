<?php

namespace Jose\Classes;

use Carbon\Carbon;
use Carbon\Factory;

class DateTime {
    public static function published_at($date) {
        return (new Carbon($date))->format('d-m Y');
        //return (new Carbon($date))->format('l F Y');
    }
    public static function full_date($date) {
        return (new Carbon($date))->format('l F Y');
        //return (new Carbon($date))->format('l F Y');
    }
};