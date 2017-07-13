UPGRADE FROM 1.1 to 1.2
=======================

Command serialization
---------------------

 * Created a separate serializer service for add opportunity change the implementation of serializer.

   ```php
   interface Serializer
   {
       public function serialize($data);

       public function deserialize($data);
   }
   ```

   Before:

   ```php
   $predis = new Client('tcp://10.0.0.1:6379'); // Predis client
   $pubsub_predis = new RedisPubSubAdapter($predis); // Predis PubSub adapter
   $serializer = new Serializer(); // Symfony serializer
   $logger = new Logger(); // PSR-3 logger
   $queue_name = 'article_queue';
   $format = 'json'; // default: predis

   $queue = new PredisPullCommandQueue($predis, $serializer, $logger, $queue_name, $format);
   $queue = new PredisUniquePullCommandQueue($predis, $serializer, $logger, $queue_name, $format);
   $queue = new PredisCommandQueue($pubsub_predis, $serializer, $logger, $queue_name, $format);
   ```

   After:

   ```php
   $predis = new Client('tcp://10.0.0.1:6379'); // Predis client
   $pubsub_predis = new RedisPubSubAdapter($predis); // Predis PubSub adapter
   $symfony_serializer = new Serializer(); // Symfony serializer
   $logger = new Logger(); // PSR-3 logger
   $queue_name = 'article_queue';
   $format = 'json'; // default: predis

   // you can create another implementation of serializer
   $serializer = new SymfonySerializer($symfony_serializer, $format);

   $queue = new PredisPullCommandQueue($predis, $serializer, $logger, $queue_name);
   $queue = new PredisUniquePullCommandQueue($predis, $serializer, $logger, $queue_name);
   $queue = new PredisCommandQueue($pubsub_predis, $serializer, $logger, $queue_name);
   ```

Subscribe queue
---------------

 * Added opportunity to use several handlers in subscribe queue.

   Before:

   ```php
   $queue = new ExecutingSubscribeCommandQueue();
   $queue->subscribe(function (Command $command) {
       // do something
   });
   // this handler override the previous handler
   $queue->subscribe(function (Command $command) {
       // do something else
   });
   ```

   After:

   ```php
   $queue = new ExecutingSubscribeCommandQueue();
   $queue->subscribe(function (Command $command) {
       // do something
   });
   // both handlers will be called
   $queue->subscribe(function (Command $command) {
       // do something else
   });
   ```

 * Added opportunity to unsubscribe of the queue.

   ```php
   interface SubscribeCommandQueue
   {
       public function subscribe(callable $handler);

       public function unsubscribe(callable $handler);
   }
   ```

   ```php
   $queue = new ExecutingSubscribeCommandQueue();

   $handler = function (Command $command) {
       // do something
   };

   $queue->subscribe($handler);
   $queue->unsubscribe($handler);
   ```
