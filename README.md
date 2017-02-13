##ArangoDB-PHP-Core

A lightweight, and at the same time flexible low-level ArangoDB client for PHP.


[![Latest Stable Version](https://poser.pugx.org/frankmayer/arangodb-php-core/v/stable)](https://packagist.org/packages/frankmayer/arangodb-php-core)
[![Total Downloads](https://poser.pugx.org/frankmayer/arangodb-php-core/downloads)](https://packagist.org/packages/frankmayer/arangodb-php-core)
[![Latest Unstable Version](https://poser.pugx.org/frankmayer/arangodb-php-core/v/unstable)](https://packagist.org/packages/frankmayer/arangodb-php-core)
[![License](https://poser.pugx.org/frankmayer/arangodb-php-core/license)](https://packagist.org/packages/frankmayer/arangodb-php-core)
[![composer.lock](https://poser.pugx.org/frankmayer/arangodb-php-core/composerlock)](https://packagist.org/packages/frankmayer/arangodb-php-core)
[![Coverage Status](https://coveralls.io/repos/frankmayer/ArangoDB-PHP-Core/badge.svg)](https://coveralls.io/r/frankmayer/ArangoDB-PHP-Core)


![PHP_Compatibility](https://img.shields.io/badge/php-7+-brightgreen.svg)
![HHVM_Compatibility](https://img.shields.io/badge/hhvm-3.12+-brightgreen.svg)


Master: [![Build Status](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core.png?branch=master)](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core)
Devel: [![Build Status](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core.png?branch=devel)](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core)

[![Join the chat at https://gitter.im/frankmayer/ArangoDB-PHP-Core](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/frankmayer/ArangoDB-PHP-Core?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

####Purpose:

The core client should serve as a flexible low level base for higher level client implementations (AR,ODM,OGM) to be built on top of it.

At this experimental stage, it does provide some abstraction of ArangoDB's API. It's still not quite clear if the api abstraction will in the end result in a different package or stay in the *Core*.
The client should generally be seen as a basis that takes away the boilerplate code of setting up requests and managing responses (headers, statuses, etc...) with ArangoDB.


####Highlights:

- Request / Response Objects
- A wrapper around injectable connectors. Two packages are being worked on: 
  - https://github.com/frankmayer/ArangoDB-PHP-Core-Curl (currently not up-todate)
  - https://github.com/frankmayer/ArangoDB-PHP-Core-Guzzle
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

Tested and Supported with PHP 7.0+ & HHVM 3.15.0+ (but will most probably work from HHVM 3.11.0 onwards)


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
Apache V2, see https://github.com/frankmayer/ArangoDB-PHP-Core/blob/devel/LICENSE.md
