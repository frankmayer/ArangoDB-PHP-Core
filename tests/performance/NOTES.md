These Notes are for test that ran on PHP 5.4.x

HttpRequest related tests:
The instantiation of the HttpRequest object is about the same between direct naming and through resolving Client::requestClass
The small difference is due to PHP internal optimization, but whichever of both gets tested first is a little slower than the second.
Even when swapping the tests.

By instantiating through the IOC container, the time doubles, but it shouldn't have considerable impact on overall performance.
If someone needs to squeeze a bit more out of it, then the IOC container can be avoided and the instantiation can be made directly or via resolving the Client:requestClass


