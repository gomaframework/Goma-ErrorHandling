Goma Error Handling
=======
GomaErrorHandler adds a basic Erorr+Exception-Handler for Goma Installations.
In addition it provides the GomaException base-class.

Customisation
---
To customise exception-handling there are two ways:
* Change the template for errors by adding a file /templates/phperror.html
* Add custom exception-handler function by adding it via ExceptionManager::registerExceptionHandler($callback, $prepend = false)
