# Little package to help with Messaging accross page redirection

## Introduction

This package provides an easy way to pass messages accross page redirection in your Fuel application.

It allows to save messages to the session in various groups and then display them easily when you need them either by group or all at once.

You can configure the layout and the allowed groups in the configuration file.

## Installation

1. Just clone the repository : `git clone git://github.com/krtek4/fuel-messages.git` 
2. Copy the content to the package directory (`fuel/packages/`)
3. Edit your application configuration file to add the messages package in `fuel/app/config/config.php`

You're all set !

If you want to configure the package, edit the config file directly in the package directory or copy it to your application configuration directory first.

## Usage

	// Add a message to the success group
	Messages\Messages::instance()->message('success', 'Good job !');

	// Add a message to the error group
	Messages\Messages::instance()->message('error', 'Crap, something went wrong :(');

	// Show the messages from the success group
	echo Messages\Messages::instance()->show('success');

	// Show all the messages from the success group
	echo Messages\Messages::instance()->show();

	// Clear the message from the error group
	Messages\Messages::instance()->clear('error');

	// Clear all the message
	Messages\Messages::instance()->clear();

By default, the messages are cleared from the session when they are shown. If you want to keep them, you can pass false to the `show()` method as second argument (pass `null` for the group argument to show all messages).

The packages is less than a hundred lines, so any other question should be easily answered by looking at the source file :)

## Configuration

All the various options of the config file are commented. It something isn't clear enough, contact me and I will update the description !

## TODO

