<?php

namespace Karan\Mod1\Plugin;

class FooterPlugin
{
    public function afterGetCopyright($subject, $result)
    {
        return '© 2026 Karan Store. All Rights Reserved.';
    }
}
