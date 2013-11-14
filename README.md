##ArangoDB-PHP-Core

A lightweight, and at the same time flexible "very"-low-level ArangoDB client for PHP.

[![Build Status](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core.png?branch=master)](https://travis-ci.org/frankmayer/ArangoDB-PHP-Core)

####Purpose:

The core client should serve as a flexible low level base for higher level client implementations (AR,ODM,OGM) to be built on top of it.

It does by itself not provide any direct abstraction of ArangoDB's API. Instead it provides a basis, that can be used by higher level drivers in order to provide that abstraction.
A basis that takes away the boilerplate code of setting up requests and managing responses (headers, statuses, etc...)


####Highlights:

- Request / Response Objects
- A wrapper around connectors (at the moment only CURL)
- Flexibility through dependency injection:
  - Inject your own connector, Request or Response Objects
     - directly
     - via configuration resolution
     - via the client class's own simple IOC container
- Register your plugins (for example a trace plugin)
- Extend the core's functionality through traits (not yet implemented)
- supports ArangoDB's Async and Batch functionality
- provides a toolbox for handling everything around communication with an ArangoDB server, such as url, parameter and header building tools.
- Includes a few test classes that provide basic testing functionality against the server and also a bit of insight on how to build a client on top of the core.


####PHP Versions:

Supported: 5.3, 5.4, 5.5

Recomended: 5.4, 5.5

Notes: 

While PHP 5.3 is still supported, the core client runs faster and with a smaller memory footprint, when used with PHP 5.4 and newer.
In other words... Do yourself a favor and upgrade to PHP 5.4 or even better to PHP 5.5.
 

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


######TODO:
- Implement missing functionality
- stabilize API
- Provide Docs


######License:
Apache V2 => see LICENSE.md file
