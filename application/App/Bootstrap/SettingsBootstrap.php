<?php

namespace App\Bootstrap;

class SettingsBootstrap
{

    public static function load()
    {
        date_default_timezone_set(getenv('APP_LOCALE'));

        error_reporting((int)getenv('CONF_REPORTING'));

        session_set_cookie_params((int)getenv('CONF_TIMESESSION'));
    }

    public static function overwriteIni(): void
    {
        ini_set('memory_limit', getenv('CONF_MEMORYLIMIT'));

        ini_set('display_errors', (string)getenv('CONF_SAVELOGS'));

        ini_set('display_startup_errors', (string)getenv('CONF_SAVELOGS'));

        ini_set('session.save_path', getenv('DIR_SESSIONS'));

        ini_set('display_startup_errors', (string)getenv('CONF_SAVELOGS'));

        ini_set('session.gc_maxlifetime', (int)getenv('CONF_TIMESESSION'));
    }


}