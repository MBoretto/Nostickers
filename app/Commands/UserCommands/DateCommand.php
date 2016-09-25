<?php
/**
 * This file is part of the nostikers bot.
 *
 * (c) Marco Boretto <marco.bore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;

/**
 * User "/date" command
 */
class DateCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'date';
    protected $description = 'Show date/time by location';
    protected $usage = '/date <location>';
    protected $version = '1.3.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->telegram->executeCommand('Generic');
    }
}
