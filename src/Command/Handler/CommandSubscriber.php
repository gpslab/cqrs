<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Command\Handler;

interface CommandSubscriber
{
    /**
     * Get called methods for subscribed commands.
     *
     * <code>
     * [
     *  [<command_name>, [<method,. ..>]],
     * ]
     * </code>
     *
     * @return array
     */
    public static function getSubscribedCommands(): array;
}
