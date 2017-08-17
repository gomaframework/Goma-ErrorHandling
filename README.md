Goma Error Handling
=======
GomaErrorHandler adds a basic Erorr+Exception-Handler for Goma Installations.
In addition it provides the GomaException base-class.

Ignorable and developer-presentable exceptions
--
* Ignorable exceptions are exceptions, which are not leading to a crash of the system, default: false
* Developer-presentable exceptions are exceptions which are printed while in Dev-Mode even if they are ignorable. 

Customisation
---
To customise exception-handling there are two ways:
* Change the template for errors by adding a file /templates/phperror.html
* Add custom exception-handler by adding a class implementing <code>ExceptionHandler</code> and register it via 
<code>ExceptionManager::registerExceptionHandler($className, $prepend = false)</code>
  * handleException:true|null Used to custom exception handling. return true if other error-handling should be stopped
  * isIgnorableException: boolean|null
  * isDeveloperPresentableException: boolean|null
