<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\console\controllers;

use Craft;
use craft\console\Controller;
use craft\helpers\Console;
use yii\console\ExitCode;

/**
 * Manage plugins.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.5.0
 */
class PluginController extends Controller
{
    /**
     * @var bool Whether the plugin uninstallation should be forced.
     * @since 3.6.14
     */
    public $force = false;

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        $options = parent::options($actionID);

        switch ($actionID) {
            case 'uninstall':
                $options[] = 'force';
                break;
        }

        return $options;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        Console::ensureProjectConfigFileExists();
        return parent::beforeAction($action);
    }

    /**
     * Installs a plugin.
     *
     * @param string $handle The plugin handle.
     * @return int
     */
    public function actionInstall(string $handle): int
    {
        $this->stdout("*** installing {$handle}" . PHP_EOL, Console::FG_YELLOW);
        $start = microtime(true);

        try {
            $success = Craft::$app->plugins->installPlugin($handle);
        } catch (\Throwable $e) {
            $success = false;
        } finally {
            if (!$success) {
                $this->stderr("*** failed to install $handle" . (isset($e) ? ": {$e->getMessage()}" : '.') . PHP_EOL . PHP_EOL, Console::FG_RED);
                return ExitCode::UNSPECIFIED_ERROR;
            }
        }

        $time = sprintf('%.3f', microtime(true) - $start);
        $this->stdout("*** installed {$handle} successfully (time: {$time}s)" . PHP_EOL . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * Uninstalls a plugin.
     *
     * @param string $handle The plugin handle.
     * @return int
     */
    public function actionUninstall(string $handle): int
    {
        $this->stdout("*** uninstalling {$handle}" . PHP_EOL, Console::FG_YELLOW);
        $start = microtime(true);

        try {
            $success = Craft::$app->plugins->uninstallPlugin($handle, $this->force);
        } catch (\Throwable $e) {
            $success = false;
        } finally {
            if (!$success) {
                $this->stderr("*** failed to uninstall $handle" . (isset($e) ? ": {$e->getMessage()}" : '.') . PHP_EOL, Console::FG_RED);
                if (!$this->force) {
                    $this->stderr('Try again with --force.' . PHP_EOL);
                }
                $this->stderr(PHP_EOL);
                return ExitCode::UNSPECIFIED_ERROR;
            }
        }

        $time = sprintf('%.3f', microtime(true) - $start);
        $this->stdout("*** uninstalled {$handle} successfully (time: {$time}s)" . PHP_EOL . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * Enables a plugin.
     *
     * @param string $handle The plugin handle.
     * @return int
     */
    public function actionEnable(string $handle): int
    {
        $this->stdout("*** enabling {$handle}" . PHP_EOL, Console::FG_YELLOW);
        $start = microtime(true);

        try {
            $success = Craft::$app->plugins->enablePlugin($handle);
        } catch (\Throwable $e) {
            $success = false;
        } finally {
            if (!$success) {
                $this->stderr("*** failed to enable $handle" . (isset($e) ? ": {$e->getMessage()}" : '.') . PHP_EOL . PHP_EOL, Console::FG_RED);
                return ExitCode::UNSPECIFIED_ERROR;
            }
        }

        $time = sprintf('%.3f', microtime(true) - $start);
        $this->stdout("*** enabled {$handle} successfully (time: {$time}s)" . PHP_EOL . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * Disables a plugin.
     *
     * @param string $handle The plugin handle.
     * @return int
     */
    public function actionDisable(string $handle): int
    {
        $this->stdout("*** disabling {$handle}" . PHP_EOL, Console::FG_YELLOW);
        $start = microtime(true);

        try {
            $success = Craft::$app->plugins->disablePlugin($handle);
        } catch (\Throwable $e) {
            $success = false;
        } finally {
            if (!$success) {
                $this->stderr("*** failed to disable $handle" . (isset($e) ? ": {$e->getMessage()}" : '.') . PHP_EOL . PHP_EOL, Console::FG_RED);
                return ExitCode::UNSPECIFIED_ERROR;
            }
        }

        $time = sprintf('%.3f', microtime(true) - $start);
        $this->stdout("*** disabled {$handle} successfully (time: {$time}s)" . PHP_EOL . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }
}
