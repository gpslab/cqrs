Pull/Push queue
===============

Queues are designed to distribute the load and delay execution of commands or transfer their execution to separate
processes.

Pull/Push is a [FIFO](https://en.wikipedia.org/wiki/FIFO_(computing_and_electronics)) queue. Pull/Push queue is
designed to explicitly pull commands from the queue. You can do this on a timer through
[cron](https://en.wikipedia.org/wiki/Cron).

The implementation of such a queue is very simple, but it has a number of shortcomings:

* Delays the execution of commands due to the timer;
* Calling to the queue wasted due to the absence of messages in the queue;
* Increase network activity;
* Load increase.

To solve these problems, we recommend using a [Pub/Sub](../pull_push/pull_push.md) queue.
