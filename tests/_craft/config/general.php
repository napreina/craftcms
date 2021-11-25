<?php

use craft\test\Craft;

/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here.
 * You can see a list of the default settings in craft/app/config/defaults/general.php
 */
return [
    'devMode' => true,
    'omitScriptNameInUrls' => true,
    'cpTrigger' => 'adminustriggerus',
    'slugWordSeparator' => '--',
    'allowUppercaseInSlug' => true,
    'securityKey' => getenv('SECURITY_KEY'),
    'useProjectConfigFile' => isset(Craft::$instance) && Craft::$instance->_getConfig('projectConfig') !== null,
];
