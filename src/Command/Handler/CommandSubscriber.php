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
     * {
     *  <command_name>: <method_name>,
     * }
     * </code>
     *
     * @return array<class-string, string>
     */
    public static function getSubscribedCommands(): array;
}
