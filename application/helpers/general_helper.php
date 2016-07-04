<?php

if (!function_exists('genHelDoPasswordHash')) 
{
    /**
     * To generate password hash
     *
     * @param string $password Input the user password
     * @return string The hash string (60 char or more)
     */
    function genHelDoPasswordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('genHelDoPasswordVerify')) 
{
    /**
     * To verify password hash
     *
     * @param string $user_password Input the user password
     * @param string $db_password Input the user password from db (hash version)
     * @return boolean TRUE if success, FALSE otherwise
     */
    function genHelDoPasswordVerify($user_password, $db_password)
    {
        return password_verify($user_password, $db_password);
    }
}

if (!function_exists('genHelDoConvertDate')) 
{
    /**
     * To change date format
     *
     * @param string $date The date
     * @param string $date_format_in The format of the date
     * @param string $date_format_out The format that you want it to be
     * @return string The date in new format
     */
    function genHelDoConvertDate($date, $date_format_in, $date_format_out)
    {
        $date = DateTime::createFromFormat($date_format_in, $date); //d/m/Y
        return $date->format($date_format_out); //Y-m-d

    }
}

if (!function_exists('genHelDoAddOneDayToDate')) 
{
    /**
     * To add one day to the date
     *
     * @param string $date The date
     * @return string The date with 1 day added
     */
    function genHelDoAddOneDayToDate($date)
    {
        $date = new DateTime($date);

        $date->modify('+1 day');
        return $date->format('Y-m-d');

    }
}






?>