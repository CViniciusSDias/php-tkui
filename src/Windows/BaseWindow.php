<?php declare(strict_types=1);

namespace PhpGui\Windows;

use PhpGui\Layouts\Grid;
use PhpGui\Layouts\Pack;
use PhpGui\Options;
use PhpGui\TclTk\TkWindowManager;
use PhpGui\Widgets\Menu\Menu;
use PhpGui\Widgets\Widget;
use PhpGui\WindowManager;

/**
 * Shares the features for window implementations.
 */
abstract class BaseWindow implements Window
{
    private Options $options;
    private WindowManager $wm;

    /**
     * Window instance id.
     */
    private int $id;

    private static int $idCounter = 0;

    /**
     * @param string $title The window title.
     */
    public function __construct(string $title)
    {
        $this->generateId();
        $this->options = $this->initOptions();
        $this->wm = $this->createWindowManager();
        $this->createWindow();
        $this->title = $title;
    }

    public function __destruct()
    {
        // TODO: unregister callback handler.
        // TODO: destroy all variables.
    }

    protected function initOptions(): Options
    {
        return new Options([
            'title' => '',
            'state' => '',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function close(): void
    {
        $this->getEval()->tclEval('destroy', $this->path());
    }

    /**
     * Create the window manager for the window.
     */
    protected function createWindowManager(): WindowManager
    {
        return new TkWindowManager($this->getEval(), $this);
    }

    /**
     * Actual window creation.
     */
    abstract protected function createWindow(): void;

    private function generateId(): void
    {
        $this->id = static::$idCounter++;
    }

    /**
     * @inheritdoc
     */
    public function widget(): string
    {
        return 'toplevel';
    }

    /**
     * @inheritdoc
     */
    public function window(): Window
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function id(): string
    {
        return 'w' . $this->id;
    }

    /**
     * @inheritdoc
     */
    public function path(): string
    {
        return '.' . $this->id();
    }

    /**
     * @inheritdoc
     */
    public function options(): Options
    {
        return $this->options;
    }

    public function __get($name)
    {
        return $this->options->$name;
    }

    public function __set($name, $value)
    {
        if ($this->options->has($name) && $this->options->$name !== $value) {
            $this->options->$name = $value;
            switch ($name) {
                case 'title':
                    $this->wm->setTitle($value);
                    break;
                case 'state':
                    $this->wm->setState($value);
                    break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function pack($widget, array $options = []): Widget
    {
        $pack = new Pack($this->getEval());
        $widgets = is_array($widget) ? $widget : array($widget);

        foreach ($widgets as $w) {
            $pack->add($w, $options);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function grid(): Grid
    {
        return new Grid($this->getEval());
    }

    /**
     * @inheritdoc
     */
    public function bind(string $event, ?callable $callback): self
    {
        return $this->bindWidget($this, $event, $callback);
    }

    /**
     * @inheritdoc
     */
    public function getWindowManager(): WindowManager
    {
        return $this->wm;
    }

    /**
     * @inheritdoc
     */
    public function setMenu(Menu $menu): Window
    {
        $this->getEval()->tclEval($this->path(), 'configure', '-menu', $menu->path());
        return $this;
    }
}