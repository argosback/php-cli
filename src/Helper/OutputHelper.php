<?php

namespace Ahc\Cli\Helper;

use Ahc\Cli\Input\Argument;
use Ahc\Cli\Input\ArgvParser as Command;
use Ahc\Cli\Input\Option;
use Ahc\Cli\Output\Writer;

/**
 * This helper helps you by showing you help information :).
 *
 * @author  Jitendra Adhikari <jiten.adhikary@gmail.com>
 * @license MIT
 *
 * @link    https://github.com/adhocore/cli
 */
class OutputHelper
{
    /**
     * @param Argument[] $arguments
     *
     * @return void
     */
    public function showArgumentsHelp(array $arguments, string $header = '', string $footer = '')
    {
        $this->showHelp('Arguments', $arguments, 4, $header, $footer);
    }

    /**
     * @param Option[] $options
     *
     * @return void
     */
    public function showOptionsHelp(array $options, string $header = '', string $footer = '')
    {
        $this->showHelp('Options', $options, 9, $header, $footer);
    }

    /**
     * @param Command[] $options
     *
     * @return void
     */
    public function showCommandsHelp(array $commands, string $header = '', string $footer = '')
    {
        $this->showHelp('Commands', $commands, 2, $header, $footer);
    }

    protected function showHelp(string $for, array $items, int $space, string $header = '', string $footer = '')
    {
        $w = new Writer;

        if ($header) {
            $w->bold($header, true);
        }

        $w->eol()->boldGreen($for . ':', true);

        if (empty($items)) {
            $w->bold('  (n/a)', true);

            return;
        }

        ksort($items);

        $maxLen = \max(\array_map('strlen', \array_keys($items)));

        foreach ($items as $item) {
            $name = $this->getName($item);
            $w->bold('  ' . \str_pad($name, $maxLen + $space))->comment($item->desc(), true);
        }

        if ($footer) {
            $w->eol()->yellow($footer, true);
        }
    }

    protected function getName($item)
    {
        $name = $item->name();

        if ($item instanceof Command) {
            return $name;
        }

        if ($item instanceof Option) {
            $name = $item->short() . '|' . $item->long();
        }

        if ($item->required()) {
            return "<$name>";
        }

        return "[$name]";
    }
}
