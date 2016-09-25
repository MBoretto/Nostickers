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
 * User "/weather" command
 */
class WeatherCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'weather';
    protected $description = 'Show weather by location';
    protected $usage = '/weather <location>';
    protected $version = '1.1.0';
    /**#@-*/


    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->telegram->executeCommand('Generic');
    }
}
