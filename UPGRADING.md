# Upgrade Guide

## Version 1 to 2
***

* There is no longer a config file, so remove any extensions (e.g. **app/Config/Chat.php**).
* Corollary, there is no longer a "silent" mode. Wrap chat interactions in a `try..catch` block if you suspect failures.
* Instead of a session key provide an implementation of `codeigniter4/authentication-implementation` for determining the current user (see [User Guide](https://codeigniter4.github.io/CodeIgniter4/extending/authentication.html)).
* Corollary, the library assumes the required function is pre-loaded (e.g. call `helper('auth')` before using `Chat`)
* Entity stored relations are now private members and will not be available in `$attributes` - extending classes should use the relation getter
