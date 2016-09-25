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
 * User "/survery" command
 */
class SurveyCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'survey';
    protected $description = 'Survery for bot users';
    protected $usage = '/survey';
    protected $version = '0.2.0';
    protected $need_mysql = true;
    /**#@-*/


    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->telegram->executeCommand('Generic');
    }
}
