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

Set up your environment by adding your API key to a `.env` file in the project
root. Use the `.env.dist` as a boilerplate.

    cp .env.dist .env
    $EDITOR .env

Usage
-----

The primary executable is `bin/linode`. You can call it directly to see a list
of available API calls.

    bin/linode

To get help on a particular command, you can use the `help` subcommand:

    bin/linode help linode.resize
