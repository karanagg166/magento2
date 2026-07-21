<?php

namespace Kunal\Mod1\Plugin;

class HeaderPlugin
{
    public function afterGetWelcome($subject, $result)
    {
        return 'Welcome to Kunal Store!';
    }
}
