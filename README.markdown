# Asynchronous cache priming with progress bars via Gearman and Memcached

This is a simple example application of how I'm using Gearman and Memcached to
provide the user feedback by using asynchronous workers to prime caches if they
are not valid

See my blog post for more details at <http://www.davedevelopment.co.uk/2011/04/04/asynchronous-cache-priming-with-progress-bars-via-gearman-memcache-and-dojo>

## Usage

### Install Gearman and Memcached, including PECL modules
<pre>
> sudo su
> apt-get install gearman libgearman-dev memcached
> pecl install memcache 
> pecl install gearman-beta
> echo "extension=memcache.so" > /etc/php5/conf.d/memcache.ini
> echo "extension=gearman.so" > /etc/php5/conf.d/gearman.ini
> /etc/init.d/apache2 restart
</pre>

### Install the application somewhere in the web root 
<pre>
> cd /var/www/
> git clone https://github.com/davedevelopment/async-demo.git
</pre>

### Set the worker going
<pre>
> cd /var/www/async-demo
> php worker.php
</pre>

### Hit the url
<pre>
> firefox http://localhost/async-demo/
</pre>

## Slim PHP Microframework

The PHP framework used in this example is availabe at <http://github.com/codeguy/Slim>

Slim is released under the MIT public license.

<http://www.slimframework.com/license>
