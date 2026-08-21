<?php

namespace Karan\Mod1\Plugin;

class HeaderPlugin
{
    public function afterGetWelcome($subject, $result)
    {
        return 'Welcome to Karan Store!';
    }
}
