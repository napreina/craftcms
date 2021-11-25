<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace crafttests\unit\services;

use Codeception\Test\Unit;
use Craft;
use craft\helpers\DateTimeHelper;
use craft\models\AssetTransform;

class AssetTransformsTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Makes sure that extending transform correctly updates it.
     *
     * @dataProvider extendTransformDataProvider
     *
     * @param AssetTransform $transform
     * @param array $parameters
     * @param array $resultCheck
     */
    public function testExtendTransform(AssetTransform $transform, array $parameters, array $resultCheck)
    {
        $extendedTransform = Craft::$app->getAssetTransforms()->extendTransform($transform, $parameters);

        foreach ($resultCheck as $property => $value) {
            self::assertSame($value, $extendedTransform->{$property});
        }
    }

    public function testExtendingTransformReturnsNewObject()
    {
        $transform = new AssetTransform(['width' => 200, 'height' => 200]);
        $extendedTransform = Craft::$app->getAssetTransforms()->extendTransform($transform, ['height' => 300]);
        self::assertNotSame($extendedTransform, $transform);
    }

    public function extendTransformDataProvider()
    {
        return [
            [
                new AssetTransform(['width' => 200, 'height' => 200]),
                ['format' => 'jpg'],
                ['width' => 200, 'height' => 200, 'format' => 'jpg'],
            ],
            [
                new AssetTransform(['width' => 200, 'height' => 200]),
                [],
                ['width' => 200, 'height' => 200],
            ],
            [
                new AssetTransform(['width' => 200, 'height' => 200]),
                ['width' => null],
                ['width' => null, 'height' => 200],
            ],
            [
                new AssetTransform(['width' => 200, 'height' => 200, 'handle' => 'square']),
                ['format' => 'jpg', 'handle' => 'rectangle'],
                ['width' => 200, 'height' => 200, 'format' => 'jpg', 'handle' => null],
            ],
            [
                new AssetTransform(['width' => 200, 'height' => 200, 'id' => 88, 'uid' => 100, 'handle' => 'square', 'dimensionChangeTime' => DateTimeHelper::currentUTCDateTime()]),
                ['format' => 'jpg'],
                ['width' => 200, 'height' => 200, 'format' => 'jpg', 'handle' => null, 'id' => null, 'uid' => null, 'dimensionChangeTime' => null],
            ],
        ];
    }
}
