<?php

namespace globalia\groots\behaviors;

use globalia\groots\GlobaliaGroots as Module;
use yii\base\Behavior;

class ApplicationBehavior extends Behavior
{
    public function getGlobalia()
    {
        return Module::getInstance();
    }
}
