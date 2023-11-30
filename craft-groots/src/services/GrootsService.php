<?php

namespace globalia\groots\services;

use Composer\DependencyResolver\Operation\MarkAliasUninstalledOperation;
use Craft;
use craft\helpers\Template;
use globalia\groots\GlobaliaGroots;
use Twig\Markup;
use yii\base\Component;


/**
 * Dev Mode Service service
 */
class GrootsService extends Component
{
    public function getModule(string $moduleFile, array $moduleVariables): Markup
    {
        $grootsPath = GlobaliaGroots::getInstance()->getSettings()->getGrootsPath();

        if(!$grootsPath) {
            throw new \Exception('Is Groots installed? Groots path not found.');
        }
        $fullFile = $grootsPath. "/modules/" . $moduleFile;

        if(!is_readable($fullFile)) {
            throw new \Exception('Is Groots installed? Module file not found: ' . $fullFile);
        }

        $source = file_get_contents($fullFile);
        $source = str_replace('@groots', $grootsPath, $source);
        $rendered = GlobaliaGroots::getInstance()->getView()->renderString($source, $moduleVariables);
        return Template::raw($rendered);
    }

    public function getComponent(string $componentFile, array $componentVariables): Markup
    {
        $grootsPath = GlobaliaGroots::getInstance()->getSettings()->getGrootsPath();

        if(!$grootsPath) {
            throw new \Exception('Is Groots installed? Groots path not found.');
        }
        $fullFile = $grootsPath. "/components/" . $componentFile;

        if(!is_readable($fullFile)) {
            throw new \Exception('Is Groots installed? Component file not found: ' . $fullFile);
        }

        $source = file_get_contents($fullFile);
        $source = str_replace('@groots', $grootsPath, $source);
        $rendered = GlobaliaGroots::getInstance()->getView()->renderString($source, $componentVariables);
        return Template::raw($rendered);
    }
}
