Command Queue
=============

`CommandBus` immediately executes the command, but sometimes you need to delay the execution of the command and for
this you can use the interface of the queue.

```php
interface CommandQueue
{
    public function publish(Command $command);
}
```

The publishing interface is general, and the interface for obtaining commands from the queue depends on the
implementation of the queue system.

We offer two options for implementing queues:

* [Pull](pull/pull.md) - Pull queue is designed to explicitly pull commands from the queue. You can do this on a timer
through [cron](https://en.wikipedia.org/wiki/Cron);
* [Subscribe](subscribe/subscribe.md) - Subscribe queue is designed for asynchronous work. The handler is called only
when the message is published in the queue.
