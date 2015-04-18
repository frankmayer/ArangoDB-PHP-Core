##ArangoDB-PHP-Core

[![Join the chat at https://gitter.im/frankmayer/ArangoDB-PHP-Core](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/frankmayer/ArangoDB-PHP-Core?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

A lightweight, and at the same time flexible "very"-low-level ArangoDB client for PHP.

Master: [![Build Status](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core.png?branch=master)](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core)
Devel: [![Build Status](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core.png?branch=devel)](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core)

[![Coverage Status](https://coveralls.io/repos/frankmayer/ArangoDB-PHP-Core/badge.png)](https://coveralls.io/r/frankmayer/ArangoDB-PHP-Core)
[![GitHub license](https://img.shields.io/github/license/mashape/apistatus.svg)]()


####Purpose:

The core client should serve as a flexible low level base for higher level client implementations (AR,ODM,OGM) to be built on top of it.

It does by itself not provide any direct abstraction of ArangoDB's API. Instead it provides a basis, that can be used by higher level drivers in order to provide that abstraction.
A basis that takes away the boilerplate code of setting up requests and managing responses (headers, statuses, etc...)


####Highlights:

- Request / Response Objects
- A wrapper around connectors (at the moment only cUrl is built in, there is a placeholder directory to also provide a wrapper to FSock). A Guzzle 5 Connector is in the works.
- Flexibility through dependency injection:
  - Inject your own connector, Request or Response Objects
     - directly
     - via configuration resolution
     - via the client class's own simple IOC container
- Register your plugins (for example a trace plugin)
- Extend the core's functionality through traits (This is not yet standardized because of the client not being stable yet)
- supports ArangoDB's Async and Batch functionality (sort of. There are some more things to do)
- provides a toolbox for handling everything around communication with an ArangoDB server, such as url-, parameter- and header-building tools.
- Includes a few test classes that provide basic testing functionality against the server and also a bit of insight on how to build a client on top of the core.


####PHP Versions:

Supported: 5.4+ & HHVM 2.3.0+


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
- [ ] stabilize plugin API
- [x] implement ArangoDB authentication
- [ ] implement basic tracer plugin
- [ ] provide docs


######License:
Apache V2 => see LICENSE.md file
