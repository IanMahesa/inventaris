<?php
if (!function_exists('localStorageDarkMode')) {
    function localStorageDarkMode()
    {
        return isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark';
    }
}
