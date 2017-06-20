Pub/Sub queue
=============

Publish/Subscribe queue is designed for asynchronous work. The handler is called only when the message is published in
the queue.

This allows you to reduce the load without interrogating once again as in the case of
[Pull/Push](../pull_push/pull_push.md) queue.

You can use any implementations of [callable type](http://php.net/manual/en/language.types.callable.php) as a queue
subscriber.

You can use one of the existing queues:

* [Executing queue](executing.md)
* [Predis queue](predis.md)
