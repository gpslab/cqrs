UPGRADE FROM 1.0 to 1.1
=======================

 * Added a common interface of command queue and use it in Pull queues.

   ```php
   interface CommandQueue
   {
       public function publish(Command $command);
   }
   ```

   Before:

   ```php
   $queue->push($command);
   ```

   After:

   ```php
   $queue->publish($command);
   ```

Renamed namespaces
------------------

 * The `GpsLab\Component\Command\Queue\PubSub` renamed to `GpsLab\Component\Command\Queue\Subscribe`.
 * The `GpsLab\Component\Command\Queue\PullPush` renamed  to `GpsLab\Component\Command\Queue\Pull`.
 * The `GpsLab\Component\Tests\Command\Queue\PubSub` renamed to `GpsLab\Component\Command\Queue\Subscribe`.
 * The `GpsLab\Component\Tests\Command\Queue\PullPush` renamed  to `GpsLab\Component\Command\Queue\Pull`.

Renamed interfaces
------------------

 * The `GpsLab\Component\Command\Queue\PubSub\CommandQueue` renamed to `GpsLab\Component\Command\Queue\Subscribe\SubscribeCommandQueue`.
 * The `GpsLab\Component\Command\Queue\PullPush\CommandQueue` renamed to `GpsLab\Component\Command\Queue\Pull\PullCommandQueue`.

Renamed classes
---------------

 * The `GpsLab\Component\Command\Queue\PubSub\ExecutingCommandQueue` renamed to `GpsLab\Component\Command\Queue\Subscribe\ExecutingSubscribeCommandQueue`.
 * The `GpsLab\Component\Command\Queue\PubSub\PredisCommandQueue` renamed to `GpsLab\Component\Command\Queue\Subscribe\PredisSubscribeCommandQueue`.
 * The `GpsLab\Component\Command\Queue\PullPush\MemoryCommandQueue` renamed to `GpsLab\Component\Command\Queue\Pull\MemoryPullCommandQueue`.
 * The `GpsLab\Component\Command\Queue\PullPush\MemoryUniqueCommandQueue` renamed to `GpsLab\Component\Command\Queue\Pull\MemoryUniquePullCommandQueue`.
 * The `GpsLab\Component\Command\Queue\PullPush\PredisCommandQueue` renamed to `GpsLab\Component\Command\Queue\Pull\PredisPullCommandQueue`.
 * The `GpsLab\Component\Command\Queue\PullPush\PredisUniqueCommandQueue` renamed to `GpsLab\Component\Command\Queue\Pull\PredisUniquePullCommandQueue`.
 * The `GpsLab\Component\Tests\Command\Queue\PubSub\ExecutingCommandQueueTest` renamed to `GpsLab\Component\Command\Queue\Subscribe\ExecutingSubscribeCommandQueueTest`.
 * The `GpsLab\Component\Tests\Command\Queue\PubSub\PredisCommandQueueTest` renamed to `GpsLab\Component\Command\Queue\Subscribe\PredisSubscribeCommandQueueTest`.
 * The `GpsLab\Component\Tests\Command\Queue\PullPush\MemoryCommandQueueTest` renamed  to `GpsLab\Component\Command\Queue\Pull\MemoryPullCommandQueueTest`.
 * The `GpsLab\Component\Tests\Command\Queue\PullPush\MemoryUniqueCommandQueueTest` renamed  to `GpsLab\Component\Command\Queue\Pull\MemoryUniquePullCommandQueueTest`.
 * The `GpsLab\Component\Tests\Command\Queue\PullPush\PredisCommandQueueTest` renamed  to `GpsLab\Component\Command\Queue\Pull\PredisPullCommandQueueTest`.
 * The `GpsLab\Component\Tests\Command\Queue\PullPush\PredisUniqueCommandQueueTest` renamed  to `GpsLab\Component\Command\Queue\Pull\PredisUniquePullCommandQueueTest`.

Renamed methods
---------------

 * The `GpsLab\Component\Command\Queue\PullPush\MemoryCommandQueue::push()` renamed to `GpsLab\Component\Command\Queue\Pull\MemoryPullCommandQueue::publish()`.
 * The `GpsLab\Component\Command\Queue\PullPush\MemoryUniqueCommandQueue::push()` renamed to `GpsLab\Component\Command\Queue\Pull\MemoryUniquePullCommandQueue::publish()`.
 * The `GpsLab\Component\Command\Queue\PullPush\PredisCommandQueue::push()` renamed to `GpsLab\Component\Command\Queue\Pull\PredisPullCommandQueue::publish()`.
 * The `GpsLab\Component\Command\Queue\PullPush\PredisUniqueCommandQueue::push()` renamed to `GpsLab\Component\Command\Queue\Pull\PredisUniquePullCommandQueue::publish()`.
