
Experimental Phase
------------------
* Adapted OO structure to better work with other custom connector middleware (like ArangoDB-PHP-Core-Guzzle for Guzzle 5)

* The provided ArangoDB API calls are now static. No need to instantiate unnecessary Objects in order to do http requests. 
  Objects can be provided from the higher level implementations, if needed.   

* Made ServerExceptions more flexible.
  It's now configurable, which HTTP Response code triggers an exception.

  Also fixed an issue with the decoding of the status header and extraction of the status phrase

* Implemented Authentication support. Throws ServerException if Server returns a 401.

* Made Client Setters chain-able (+tests)

* Refactored and testing of Plugin functionality

* Implemented Async Jobs management and basic tests for examples

* Fixed Batch functionality

* Lots of cleanup.

* (Hopefully) stabilized IoC container

* Removed constructor parameters from all classes except the Client

* Reorganized tests directory. Included performance test suite

* IDE hinting...

* Basic IOC Container functionality implemented. Needs some more work and testing

* Added x-arango-version header functionality.

* Initial commit


