<?php

namespace App\Helpers;

class Constrains
{
    const LINK_REGEX = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
    const LINK_REPLACE = '<a href="$1" target="_blank">$1</a>';
    const EMAIL_REGEX = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/';
    const EMAIL_REPLACE = '<a href="mailto:$0">$0</a>';
    const PHONE_REGEX = '/\+?(88)?0?1[3456789][0-9]{8}\b/';
    const PHONE_REPLACE = '<a href="tel:$0">$0</a>';
}
