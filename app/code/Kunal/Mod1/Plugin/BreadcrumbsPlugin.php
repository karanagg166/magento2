<?php

namespace Kunal\Mod1\Plugin;

class BreadcrumbsPlugin
{
    public function beforeAddCrumb(
        $subject,
        $crumbName,
        $crumbInfo
    ) {
        if (isset($crumbInfo['label'])) {
            $crumbInfo['label'] =
                'Hummingbird ' . $crumbInfo['label'];
        }

        return [$crumbName, $crumbInfo];
    }
}
