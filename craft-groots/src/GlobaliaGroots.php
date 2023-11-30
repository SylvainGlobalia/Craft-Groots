<?php

namespace globalia\groots;

use Craft;
use craft\web\twig\variables\CraftVariable;
use yii\base\Module as BaseModule;

use globalia\groots\behaviors\ApplicationBehavior;

use craft\base\Plugin;
use craft\base\Model;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use globalia\groots\services\GrootsService;
use globalia\groots\variables\GrootsVariable;
use globalia\groots\twig\Extension;
use globalia\groots;
use globalia\groots\web\View;


use yii\base\Event;

/**
 * globalia/craft-groots plugin
 *
 * @method static GlobaliaGroots getInstance()
 */
class GlobaliaGroots extends Plugin
{
    private $view;

    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    protected function createSettingsModel(): ?Model
    {
        return new \globalia\groots\models\Settings();
    }

    protected function settingsHtml(): ?string
    {
        return \Craft::$app->getView()->renderTemplate(
            '_globalia-craft-groots/settings',
            [ 'settings' => $this->getSettings() ]
        );
    }

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
                'groots'  => ['class' => GrootsService::class],

            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        Craft::setAlias('@globalia/groots', __DIR__);
        Craft::configure($this, self::config());

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function () {
            Craft::$app->attachBehavior('globaliaApplicationBehavior', new ApplicationBehavior());
            $this->view = new View();
            $grootsPath = GlobaliaGroots::getInstance()->getSettings()->getGrootsPath();

            $this->view->setTemplatesPath($grootsPath . '/modules');
            $this->attachEventHandlers();
            // ...
        });
    }

    public function getView(): View
    {
        return $this->view;
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var GrootsVariable $variable */
                $variable = $event->sender;
                $variable->set('groots', GrootsVariable::class);
            }
        );

        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function (Event $event) {
                $event->roots['groots'] = GlobaliaGroots::getInstance()->getSettings()->getGrootsPath();
            }
        );

        if (Craft::$app->getRequest()->getIsSiteRequest()) {
            // Instantiate + register the extension:
            $extension = new Extension();
            Craft::$app->getView()->registerTwigExtension($extension);
            $this->view->registerTwigExtension($extension);
        }

    }
}
