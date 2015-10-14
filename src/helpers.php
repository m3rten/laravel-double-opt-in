<?php

if (! function_exists('message')) {

    /**
     * Set a flash message and bootstrap color
     *
     * @param $message
     * @param $type
     */
    function message($message, $type)
    {
        \Session::flash('message', $message);
        \Session::flash('message-alert', $type);
    }
}
