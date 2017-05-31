# Command bus

The command bus is designed to handle commands. It's a
[publish–subscribe](https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern) pattern.

You can use `HandlerLocatedCommandBus`. This bus use `CommandHandlerLocator` for search the handler for specific
command.

You can create custom bus implementing the interface `CommandBus`.
