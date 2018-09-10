SCRandomArticle
===============

This plugin allows you to create a random article URL for each of your categories.

It works by intercepting page requests to addresses matching the specified pattern, and re-routing them to a random article in the requested category.

It does not place links to the random article URLs on your site; it is up to you to do that separately, eg as menu entries.

Version History
----------------

* 1.0.0


Installation
----------------

This is a standard Joomla plugin. Installation is via Joomla's extension manager. As with all plugins, remember that it must also be activated after being installed.


Usage
----------------

Firstly, set the URL pattern you want for the random links. The default value for this is `[category]/random`, which should be a good starting point. You may change it though if you wish.

Now simply navigating to `http://yoursite.com/mycategory/random/` will result in the request being redirected to a random article in the `mycategory` category.

You may now add menu entries or other links pointing to this URL in order to use your random links.


Motivation
----------------

This plugin was written after searching for an existing one to do the job proved fruitless. There are some other similar plugins and modules, but none of them were quite right; some were too old and unsupported and others didn't quite do what I wanted. So I wrote it myself.


Todo List and Known Issues
--------------------------

* Todo: Add caching to the DB query so we don't have to load the article list every time.
* Todo: I've not tested it with nested categories; I doubt it will work correctly.
* Todo: Add more options; eg only featured articles, etc.
* Caveat: If you have an actual article with a title like `random` that matches the redirect URL in this plugin, then they may clash. Try to avoid this.


License
----------------
As with all Joomla extensions and Joomla itself, this plugin is licensed under the GPL. The full license document should have been included with the source code.
