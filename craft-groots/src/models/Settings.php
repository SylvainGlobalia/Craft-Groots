<?php

namespace globalia\groots\models;

use craft\base\Model;

class Settings extends Model
{
    public $foo = 'defaultFooValue';
    public $bar = 'defaultBarValue';
    public $groots_path = '/groots/workspacess';
    public static $groots_path2 = '/groots/workspace';

    public function defineRules(): array
    {
        return [
            [['foo', 'bar'], 'required'],
            // ...
        ];
    }

    public function getGrootsPath()
    {
        return $this->groots_path;
    }

    public static function getStaticGrootsPath()
    {
        return self::$groots_path;
    }

    public function getSettings()
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
            'groots_path' => $this->groots_path,
            'groots_path2' => self::$groots_path2,
        ];
    }

}