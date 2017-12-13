# CEHD Routes, Templates, SASS & JavaScript


This is an "app" in a very strict sense, but we've tried to keep the back end
logic to a minimum to keep this app very small and fast.

The app uses [Twig for templates](http://twig.sensiolabs.org/) and [the Slim
framework for routing](https://www.slimframework.com/). What does that
mean? Let's talk requests.

[Go here for more detailed documentation](/docs/).

## Requests

Let's imagine a web server hosting static HTML pages. Those are just plain text
files as I'm sure you know. A browser sends a request to the server. If the
server can find a file that matches the request it returns a status code of
`200 OK` and sends the contents of the file back. (If the server can't find it,
it sends a `404` status code back.)

We're abstracting that transaction a little. The server is configured to send
all requests for sites that use this application to
`public/<website>/index.php`.  Thus requests for the Ed Psych site go to
`public/edpsych/index.php`.

That file is written to route requests to their proper Twig template or return
a 404 if there is not a matching route. Slim handles routing requests to the
right place and sending a response back. Twig is the template engine.

## Templates

Where is the content? Rather than a content management system that would
require a database, we're keeping the content in some templates. This is fast
and secure.  It also allows us to take advantage of Twig's features and put a
little logic in the back end. More on that later.

### Finding The Right Template

Templates are in the `templates` directory. The template directory structure
will closely match the structure of the site. The template for any main section
of a site will be named `index.html`. E.g, the page for edpsych/about/ will be
at `templates/edpsych/about/index.html`.

### Editing Templates

[Twig for Template Designers](http://twig.sensiolabs.org/doc/templates.html)
has a lot of good information. Here are the basics.

Templates can "extend" other templates. That allows us to reuse code across
pages and entire sites. E.g., the University header and footer. That code can
exist in a single template and other templates can *extend* it and inject
code into "blocks."

#### Blocks

We start with a [`base.html`](templates/layout/base.html) template that
contains all the code that will be common from site to site. That template
has several blocks defined including `title` and `main_content`. If you
include a block in your template called "title", it will populate the
title element from the base template.

```
{# title element from base.html #}
<title>{% block title %}{% endblock %}</title>
```

```
{# title block in another template; will populate page title element #}
{% block title %}
    Title of my page
{% endblock %}
```

Two things to note: blocks are defined by `{%` the block name `%}` and a closing
`{% endblock %}` statement. Content within the block is inserted into
the parent template where that same block is defined.

```
    base.html
    |-- title block
    |
    |----index.html
    |------ title block (content here gets inserted into the title block in base.html above)
```

[See the templates directory for more details on specific templates](templates).

#### Variables

It's also possible to define variables for reuse within a template. For example,
you may want the `<h1>` to have the same content as the page title element.

Variables are set using the `set` keyword enclosed in `{% %}`.

```
{% set my_variable = "My variable value" %}
```

Once variables are set, they can be inserted anywhere in the template by
enclosing the variable name inside double curly braces.

```
{{ my_variable }}{# will render "My variable value" #}
```

To use the same value for the title element as the `<h1>` tag you would do the
following:

```
{# set the variable #}
{% set page_title = "About the Department of Educational Psychology" %}

{# use it to set both the page title and h1 #}
{% block title %}{{ page_title }}{% endblock %}
{% block heading_1 %}{{ page_title }}{% endblock %}
```

Variables are good for most cases when you find you're repeating something on a
page or across pages.

## Local dev environment setup

1. [Install Lando]((https://docs.devwithlando.io/installation/installing.html).
2. run `lando start`.

## Deprecated dev environment setup instructions

*This is not the preferred method.* Use the Lando method above instead. This is
just here so someone in the future will appreciate how complicated the local
dev setup once was.

TODO: Delete this whole section if you want to.

Whenever making changes to the back end, it's a good idea to work on a local
clone first. Here are some instructions for getting things up and running.
These instructions assume a *nix environment like OS X or Linux.

### Install VirtualBox and vagrant

* [Install VirtualBox](https://www.virtualbox.org/wiki/Downloads)
* [Install vagrant](https://www.vagrantup.com/downloads.html)

### Clone scotch-box

[Scotch-box](https://github.com/scotch-io/scotch-box) is a Vagrant box that
has a lot of what we need to run the site locally. But not all, so don't run
`vagrant up` just yet.

Put this in a directory where you organize your other projects.

Edit your `Vagrantfile` to look like this:

    # -*- mode: ruby -*-
    # vi: set ft=ruby :

    Vagrant.configure("2") do |config|

        config.vm.define "cehd.dev" do |cehd|
            cehd.vm.box = "scotch/box"
            cehd.vm.network "private_network", ip: "192.168.33.10" # <= If you add more sites, watch for clashing IP addresses
            cehd.vm.hostname = "scotchbox.dev"
            cehd.vm.synced_folder "./cehd-app_routing", "/var/www", :mount_options => ["dmode=777", "fmode=666"] # <= Here's where we take advantage of Vagrant's folder syncing
        end

        # Optional NFS. Make sure to remove other synced_folder line too
        #config.vm.synced_folder ".", "/var/www", :nfs => { :mount_options => ["dmode=777","fmode=666"] }

    end

### Edit `/etc/hosts`

There is some risk with this step so be careful. If you follow these
instructions exactly, you should be safe.

#### Back up your original `/etc/hosts` file

    sudo cp /etc/hosts /etc/hosts_original

Enter your password when prompted. Now we have a copy of your hosts file in
case things go wrong.

#### Add the private IP address to `/etc/hosts`

    sudo vim /etc/hosts

Your screen should look something like this:


    ##
    # Host Database
    #
    # localhost is used to configure the loopback interface
    # when the system is booting.  Do not change this entry.
    ##
    127.0.0.1	localhost
    255.255.255.255	broadcasthost
    ::1             localhost

Our IP address above for the private network is `192.168.33.10`. We're going
to create a local domain called `cehd.dev` for that IP address. **Follow these
instructions precisely.**

1. Type `G`, the vim command to take you to the bottom of the file.
2. Next type `a`, to add a line and put vim into edit mode.
3. Now type `192.168.33.10 cehd.dev`
4. Hit the `esc` key to get back to Normal mode
5. Type `:wq`, which tells vim to write the changes and quit

Now `cd` into the scotch-box directory and clone this project there.

##### If things go wrong after this

If for some reason you have network troubles after editing this file you can
restore `/etc/hosts` by running

    sudo cp /etc/hosts_original/ /etc/hosts

Enter your password when prompted.

### Install Composer

[Composer](https://getcomposer.org/download/) is a dependency manager for PHP.
We rely on a lot of third party packages. Composer will manage them for you.

We recommend you move the composer binary somewhere in your path. Let's say
`composer.phar` is in your current working directory. Run:

    mv composer.phar /usr/local/bin/composer

Now if you run `which composer` you should see: `/usr/local/bin/composer`

Now `cd` into the root of this project and run `composer install`. That should
install all the PHP dependencies for this project. They go in `/vendor` and are
not tracked with git.

### Install Node (the right way)

[Follow these instructions for installing `nvm` (Node Version
Manager)](http://yoember.com/nodejs/the-best-way-to-install-node-js/).

This will set you up so that globally installed node packages get installed
where you have permission to add them without `sudo`. Note: some of our node
dependencies require node v6.7.0. You can used `nvm` to install that version
of node.

Now `cd` into the root for this project and run `npm install`. As with composer
above, this will install all the node dependencies in `/node_modules`. Those
packages are not tracked either.

### Start the VM

    vagrant up

This will take a few minutes the first time. Unfortunately the VM doesn't come
with a PHP-XML dependency that we need, but that's easy to fix. Run these
commands:

1. `vagrant ssh cehd.dev` to ssh into the Vagrant box. You're now in Ubuntu!
2. `sudo apt-get install php7.0-xml`
3. `sudo vim /etc/php/7.0/apache2/php.ini` to edit the `php.ini` file with vim
4. type `/php_xmlrpc` and hit the `return` key (you should be at a line that reads `;extension=php_xmlrpc.dll`)
5. type `0` (the number zero) to take the cursor to the beginning of the line
6. type `x` to delete the ";" character
7. type `:wq` to save the change and quit vim
8. type `sudo service apache2 restart` to restart Apache
9. type `exit` to log out of the Vagrant box

Now PHP in our VM should have everything we need. You should be able to go to
`cehd.dev` and see a working copy of the site.

TODO: Fork the scotchbox repo and make these changes to our fork so some of
this setup comes out-of-the-box. (Pun intended.)

## Bonus round

[Install Homebrew](https://brew.sh/).
