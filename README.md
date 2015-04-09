##ArangoDB-PHP-Core

A lightweight, and at the same time flexible "very"-low-level ArangoDB client for PHP.

[![Build Status](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core.png?branch=master)](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core)

[![Coverage Status](https://coveralls.io/repos/frankmayer/ArangoDB-PHP-Core/badge.png)](https://coveralls.io/r/frankmayer/ArangoDB-PHP-Core)


####Purpose:

The core client should serve as a flexible low level base for higher level client implementations (AR,ODM,OGM) to be built on top of it.

It does by itself not provide any direct abstraction of ArangoDB's API. Instead it provides a basis, that can be used by higher level drivers in order to provide that abstraction. A basis that takes away the boilerplate code of setting up requests and managing responses (headers, statuses, etc...)


####Highlights:

- Request / Response Objects
- A wrapper around connectors (at the moment only CURL)
- Flexibility through dependency injection:
  - Inject your own connector, Request or Response classes
     - directly
     - via configuration resolution
     - via the client class's own simple IOC container
- Register your plugins (for example a trace plugin)
- Extend the core's functionality through traits (not yet implemented)
- supports ArangoDB's Async and Batch functionality
- provides a toolbox for handling everything around communication with an ArangoDB server, such as url-, parameter- and header-building tools.
- Includes a few test classes that provide basic testing functionality against the server and also a bit of insight on how to build a client on top of the core.


####PHP Versions:

Supported: 5.4 (unless functionality requiring 5.5+ is to be implemented), 5.5, 5.6, HHVM 2.3.0+


#####Caution:
This project is at the moment in a __highly experimental__ phase.
The API is not yet stable and there most probably will be significant changes to it.

So, it's not recommended to build anything critical on top of it yet. ;)
But... stay tuned...


#####Contributing

As the project is very fresh and in a highly experimental state, it's not yet open to pull requests.
But I'd love to see contributions after the initial experimental phase is over. :)
I'll let you know via this readme and my Twitter-feed: https://twitter.com/frankmayer_
Thanks !!


###### Major Todo's:
- [x] stabilize plugin API
- [x] implement ArangoDB authentication
- [ ] implement basic tracer plugin
- [ ] provide docs


######License:
Apache V2 => see LICENSE.md file
