<?php

use PhpGui\Layouts\Pack;
use PhpGui\Widgets\Menu\Menu;
use PhpGui\Widgets\Menu\MenuCheckItem;
use PhpGui\Widgets\Menu\MenuItem;
use PhpGui\Widgets\Menu\MenuRadioGroup;
use PhpGui\Widgets\Menu\MenuRadioItem;
use PhpGui\Widgets\Scrollbar;
use PhpGui\Widgets\Text;

require_once dirname(__FILE__) . '/DemoAppWindow.php';

$demo = new class extends DemoAppWindow
{
    private Text $text;

    public function __construct()
    {
        parent::__construct('Menu demo');
        $this->appMenu();
        $this->text = $this->makeTextbox();
        $this->bind('Control-q', [$this->app, 'quit']);
    }

    protected function appMenu()
    {
        $menu = new Menu($this);

        $itemCallback = [$this, 'logMenuItem'];

        $menu->addMenu('_File')
             ->addItem(new MenuItem('_New', $itemCallback, ['accelerator' => 'Ctrl-N']))
             ->addSeparator()
             ->addItem(new MenuItem('_Open', $itemCallback))
             ->addItem(new MenuItem('_Save', $itemCallback))
             ->addItem(new MenuItem('Save _As...', $itemCallback))
             ->addSeparator()
             ->addItem(new MenuItem('_Quit', [$this->app, 'quit'], ['accelerator' => 'Ctrl-Q']))
            ;

        $checkCallback = [$this, 'logCheckItem'];
        $radioCallback = [$this, 'logRadioItem'];

        $menu->addMenu('_Edit')
             ->addItem(new MenuItem('_Find...', $itemCallback, ['accelerator' => 'Ctrl-F']))
             ->addItem(new MenuItem('_Replace...', $itemCallback, ['accelerator' => 'Ctrl-R']))
             ->addSeparator()
             ->addItem(new MenuCheckItem('_Wrap lines', true, $checkCallback))
             ->addItem(new MenuCheckItem('_Show cursor pos', false, $checkCallback))
             ->addSeparator()
             ->addGroup(new MenuRadioGroup([
                new MenuRadioItem('Radio _1', 1),
                new MenuRadioItem('Radio _2', 2),
                new MenuRadioItem('Radio _3', 3)
             ], 2, $radioCallback))
             ;

        $this->setMenu($menu);
    }

    protected function makeTextbox(): Text
    {
        $yscr = new Scrollbar($this);
        $txt = new Text($this);
        $txt->yScrollCommand = $yscr;
        $this->pack($yscr, ['side' => Pack::SIDE_RIGHT, 'fill' => Pack::FILL_Y]);
        $this->pack($txt, ['fill' => Pack::FILL_BOTH, 'expand' => true]);
        return $txt;
    }

    public function logMenuItem(MenuItem $item)
    {
        $this->text->append("Menu item: {$item->label}\n");
    }

    public function logCheckItem(MenuCheckItem $item)
    {
        $value = $item->getValue() ? 'true' : 'false';
        $this->text->append("Check: {$item->label}, value: {$value}\n");
    }

    public function logRadioItem(MenuRadioItem $item)
    {
        $value = (string) $item->getValue();
        $this->text->append("Radio: {$item->label}, value: {$value}\n");
    }
};

$demo->run();
