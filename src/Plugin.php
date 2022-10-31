<?php

namespace Newism\UxComponents;

use benf\neo\elements\Block;
use benf\neo\elements\db\BlockQuery;
use benf\neo\Field;
use Craft;
use craft\base\Model;
use craft\db\Query;
use Newism\UxComponents\behaviors\NeoBlockBehavior;
use Newism\UxComponents\behaviors\NeoBlockQueryBehavior;
use Newism\UxComponents\behaviors\NeoFieldBehavior;
use Newism\UxComponents\models\SettingsModel;
use Newism\UxComponents\services\BlockToComponentTransformerInterface;
use Newism\UxComponents\services\UxComponentRenderer;
use Newism\UxComponents\twig\TwigExtension;
use Newism\UxComponents\web\assets\cp\CpAsset;
use nystudio107\pluginvite\services\VitePluginService;
use Stripe\Util\Set;
use yii\base\Event;

/**
 * @property-read VitePluginService $vite
 * @property-read UxComponentRenderer $uxComponentRenderer
 * @property-read BlockToComponentTransformerInterface $blockToComponentTransformer
 */
class Plugin extends \craft\base\Plugin
{
    public bool $hasCpSettings = true;
    public $id = 'uxcomponents';

    public function init()
    {
        /** @var SettingsModel $settings */
        $settings = $this->getSettings();

        $this->set('uxComponentRenderer', [
            'class' => UxComponentRenderer::class,
            'types' => $settings->types ?? [],
        ]);

        $this->registerNeoBehaviors();
        $this->registerTwigExtensions();

        parent::init();
    }

    /**
     * Register Neo Block, Field and Query Behaviors
     */
    public function registerNeoBehaviors(): void
    {
        Event::on(
            Block::class,
            Model::EVENT_DEFINE_BEHAVIORS,
            function (craft\events\DefineBehaviorsEvent $event) {
                $event->sender->attachBehaviors([
                    NeoBlockBehavior::class,
                ]);
            }
        );

        Event::on(
            Field::class,
            Model::EVENT_DEFINE_BEHAVIORS,
            function (craft\events\DefineBehaviorsEvent $event) {
                $event->sender->attachBehaviors([
                    NeoFieldBehavior::class,
                ]);
            }
        );

        Event::on(
            BlockQuery::class,
            Query::EVENT_DEFINE_BEHAVIORS,
            function (craft\events\DefineBehaviorsEvent $event) {
                $event->sender->attachBehaviors([
                    NeoBlockQueryBehavior::class,
                ]);
            }
        );
    }

    public static function config(): array
    {
        return [
            'components' => [
                'vite' => [
                    'class' => VitePluginService::class,
                    'assetClass' => CpAsset::class,
                    'useDevServer' => true,
                    'devServerPublic' => 'http://localhost:4001/',
                    'errorEntry' => 'js/main.js',
                    'cacheKeySuffix' => '',
                    'devServerInternal' => 'http://localhost:4001/',
                    'checkDevServer' => false,
                    'includeReactRefreshShim' => false,
                ],
            ],
        ];
    }

    /**
     * Register an asset with Vite
     */
    public function registerAssetWithVite(string $path): void
    {
        $scriptOptions = [
            'depends' => [CpAsset::class],
            'onload' => null,
        ];

        $styleOptions = [
            'depends' => [CpAsset::class],
        ];

        $this->vite->register($path, false, $scriptOptions, $styleOptions);

        // Provide nice build errors - only in dev
        if ($this->vite->devServerRunning()) {
            $this->vite->register('@vite/client', false);
        }
    }

    /**
     * @return void
     */
    public function registerTwigExtensions(): void
    {
        Craft::$app->view->registerTwigExtension(new TwigExtension());
    }

    /**
     * Create a settings model
     */
    protected function createSettingsModel(): SettingsModel
    {
        return new SettingsModel();
    }

    /**
     * Render the settings html
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->getView()->renderTemplate(
            'uxcomponents/settings',
            ['settings' => $this->getSettings()]
        );
    }
}