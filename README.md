linode-api
==========

A modern PHP implementation of the Linode API.

Installation
------------

If you don't already have composer installed, install it:

    cd ~/bin
    curl -sS https://getcomposer.org/installer | php

Clone the repo and install the dependencies:

    git clone git@github.com:mgirouard/linode-api.git
    cd linode-api
    composer.phar install

Usage
-----

The primary executable is `bin/linode`. You can call it directly to see a list
of available API calls.

    bin/linode

To get help on a particular command, you can use the `help` subcommand:

    bin/linode help linode.resize

In order to perform any request, you'll need to tell the executable your API
key. At present it expects an environmental variable to be set:

    export LINODE_API_KEY=thisisnotarealapikey
    bin/linode test.echo --DATA='foo=bar'
