##ArangoDB-PHP-Core

A lightweight, and at the same time flexible "very"-low-level ArangoDB client for PHP.

[![Join the chat at https://gitter.im/frankmayer/ArangoDB-PHP-Core](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/frankmayer/ArangoDB-PHP-Core?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)


Master: [![Build Status](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core.png?branch=master)](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core)
Devel: [![Build Status](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core.png?branch=devel)](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core)


[![Coverage Status](https://coveralls.io/repos/frankmayer/ArangoDB-PHP-Core/badge.png)](https://coveralls.io/r/frankmayer/ArangoDB-PHP-Core)
[![Hex.pm](https://img.shields.io/hexpm/l/plug.svg)](https://github.com/frankmayer/ArangoDB-PHP-Core/blob/devel/LICENSE.md)


####Purpose:

The core client should serve as a flexible low level base for higher level client implementations (AR,ODM,OGM) to be built on top of it.

At this experimental stage, it does provide some abstraction of ArangoDB's API. It's still not quite clear if the api abstraction will in the end result in a different package or stay in the *Core*.
The client should generally be seen as a basis that takes away the boilerplate code of setting up requests and managing responses (headers, statuses, etc...) with ArangoDB.


####Highlights:

- Request / Response Objects
- A wrapper around injectable connectors. Two packages are already available: https://github.com/frankmayer/ArangoDB-PHP-Core-Curl and https://github.com/frankmayer/ArangoDB-PHP-Core-Guzzle
- Flexibility through dependency injection:
  - Inject your own connector, Request or Response Objects
     - directly
     - via configuration resolution
     - via the client class's own simple IOC container
- Register your plugins (for example a trace plugin)
- Extend the core's functionality through traits *(This is still in the makings)*
- supports ArangoDB's Async and Batch functionality
- provides a toolbox for handling everything around communication with an ArangoDB server, such as url-, parameter- and header-building tools.
- Includes a few test classes that provide basic testing functionality against the server and also a bit of insight on how to build a client on top of the core.


####PHP Versions:

Supported: PHP 7.0+ & HHVM 3.11.0+


#####Caution:
This project is at the moment in a __highly experimental__ phase.
**The API is not yet stable and there most probably will be significant changes to it.**

So, it's not recommended to build anything critical on top of it yet. ;)
But... stay tuned...


#####Contributing

As the project is still in a highly experimental state, it's not yet open to pull requests.
But I'd love to see contributions after the initial experimental phase is over. :)
I'll let you know via this readme and my Twitter-feed: https://twitter.com/frankmayer_
Thanks !!


###### Major Todo's:
- [ ] stabilize contracts for interoperability 
- [ ] stabilize plugin API
- [x] implement ArangoDB authentication
- [ ] implement basic tracer plugin
- [x] provide docs


######License:
Apache V2 => see LICENSE.md file
