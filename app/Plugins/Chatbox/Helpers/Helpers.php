<?php
if (!function_exists('cbc')) {
    function cbc()
    {
        return ChatboxCores::inst();
    }
}
