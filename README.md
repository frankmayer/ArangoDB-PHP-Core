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
- Register your plugins (for example a trace plugin)
- Extend the core's functionality through traits (not yet implemented)
- supports ArangoDB's Async and Batch functionality
- provides a toolbox for handling everything around communication with an ArangoDB server, such as url, parameter and header building tools.
- Includes a few test classes that provide basic testing functionality against the server and also a bit of insight on how to build a client on top of the core.


#####Caution:
This project is at the moment in a __highly experimental__ phase.
The API is not yet stable and there most probably will be significant changes to it.

So it's not recommended to build anything critical on top of it yet. ;)
But... stay tuned...


######TODO:
- Implement missing functionality
- Implement Travis Testing
- Provide Docs


######License:
Apache V2 => see LICENSE.md file
