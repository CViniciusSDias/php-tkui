<?php declare(strict_types=1);

namespace TclTk\Widgets;

use TclTk\Options;
use TclTk\Tcl;

/**
 * Implementation of Tk entry widget.
 *
 * @link https://www.tcl.tk/man/tcl8.6/TkCmd/entry.htm
 */
class Entry extends Widget
{
    /**
     * States for the 'state' option.
     */
    const STATE_NORMAL = 'normal';
    const STATE_READONLY = 'readonly';
    const STATE_DISABLED = 'disabled';

    public function __construct(TkWidget $parent, string $value = '', array $options = [])
    {
        parent::__construct($parent, 'entry', 'e', $options);
        if ($value !== '') {
            $this->setValue($value);
        }
    }

    /**
     * @inheritdoc
     */
    protected function initWidgetOptions(): Options
    {
        return new Options([
            'disabledBackground' => null,
            'disabledForeground' => null,
            'invalidCommand' => null,
            'readonlyBackground' => null,
            'show' => null,
            'state' => null,
            'validate' => null,
            'validateCommand' => null,
            'width' => null,
        ]);
    }

    /**
     * Returns the entry's string.
     *
     * @link https://www.tcl.tk/man/tcl8.6/TkCmd/entry.htm#M45
     */
    public function get(): string
    {
        return $this->call('get');
    }

    /**
     * Delete one or more elements of the entry.
     *
     * @link https://www.tcl.tk/man/tcl8.6/TkCmd/entry.htm#M44
     */
    public function delete(int $first, int $last = 0): self
    {
        if ($last > 0) {
            $this->call('delete', $first, $last);
        } else {
            $this->call('delete', $first);
        }
        return $this;
    }

    /**
     * Clears the current entry string.
     */
    public function clear(): self
    {
        $this->call('delete', 0, 'end');
        return $this;
    }

    /**
     * Insert a string just before the specified index.
     *
     * @link https://www.tcl.tk/man/tcl8.6/TkCmd/entry.htm#M48
     */
    public function insert(int $index, string $str): self
    {
        $this->call('insert', $index, Tcl::quoteString($str));
        return $this;
    }

    /**
     * Sets the new entry's string.
     */
    public function setValue(string $value): self
    {
        $this->clear();
        return $this->insert(0, $value);
    }

    /**
     * Arrange for the insertion cursor to be displayed just before the character given by index.
     *
     * @link https://www.tcl.tk/man/tcl8.6/TkCmd/entry.htm#M46
     */
    public function insertCursor(int $index): self
    {
        $this->call('icursor', $index);
        return $this;
    }
}