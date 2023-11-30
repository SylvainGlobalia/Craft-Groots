<?php

namespace globalia\groots\variables;

use Craft;
// use craft\elements\Entry;
use globalia\groots\GlobaliaGroots;


class ToolkitVariable
{
    public function getInfo()
    {
        return GlobaliaGroots::getInstance()->getSettings()->getGrootsPath();
    }

    public function getGroots($moduleName = "", $moduleVariables = [])
    {
        return GlobaliaGroots::getInstance()->get('groots')->getModule($moduleName, $moduleVariables);
    }
}
