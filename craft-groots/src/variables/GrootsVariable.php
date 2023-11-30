<?php

namespace globalia\groots\variables;

use Craft;
use globalia\groots\GlobaliaGroots;

class GrootsVariable
{
    public function getModule(string $moduleName, array $moduleVariables)
    {
        return GlobaliaGroots::getInstance()->get('groots')->getModule($moduleName, $moduleVariables);
    }

    public function getComponent(string $componentName, array $componentVariables)
    {
        return GlobaliaGroots::getInstance()->get('groots')->getComponent($componentName, $componentVariables);
    }

    public function getInfo()
    {
        return GlobaliaGroots::getInstance()->getSettings()->getGrootsPath();
    }


}
