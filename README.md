# Tatter\Chat

Embedded chat widget for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-chat/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-chat/actions/workflows/phpunit.yml)
[![](https://github.com/tattersoftware/codeigniter4-chat/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-chat/actions/workflows/phpstan.yml)
[![](https://github.com/tattersoftware/codeigniter4-chat/workflows/Deptrac/badge.svg)](https://github.com/tattersoftware/codeigniter4-chat/actions/workflows/deptrac.yml)
[![Coverage Status](https://coveralls.io/repos/github/tattersoftware/codeigniter4-chat/badge.svg?branch=develop)](https://coveralls.io/github/tattersoftware/codeigniter4-chat?branch=develop)

## Quick Start

1. Install with Composer: `> composer require tatter/chat`
2. Update the database: `> php spark migrate --all`
3. Publish asset files: `> php spark publish`
4. Add Chat JS to your layout: `<script><?= view('Tatter\Chat\Views\javascript') ?></script>`
4. Add a chat to any view: `<?= chat('my-first-chat') ?>`

## Features

**Chat** allows developers to add a lightweight Bootstrap-style chat client to any page.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
```shell
> composer require tatter/chat
```

Or, install manually by downloading the source files and adding the directory to
**app/Config/Autoload.php**.

Once the files are downloaded and included in the autoload, run any library migrations
to ensure the database is setup correctly:
```shell
> php spark migrate --all
```

### Assets

**Chat** has JavaScript code as well as asset dependencies that need to be included
with any view that has a conversation on it. Assets are managed by the
[Tatter\Assets](https://github.com/tattersoftware/codeigniter4-assets) library; you can
publish all files with CodeIgniter's Publisher: `spark publish`. Be sure to configure
the **Assets** filter and apply it to routes (see docs).

### Authentication

**Chat** uses `Tatter\Users` to determine participants username and display name. You must
be sure to include a package that provides `codeigniter4/authentication-implementation`
(like **Shield**) or make your own (see [Authentication](https://codeigniter4.github.io/CodeIgniter4/extending/authentication.html)
for framework requirements).

## Usage

The easiest way to start a chat is with the helper. Load the helper file (`helper('chat')`)
and then use the `chat($uid, $title)` command wherever you would use a partial view:

```html
<div id="main">
	<h3>Yellow Widgets</h3>
	<p>Main product info here!</p>
	
	<aside>
		<?= chat('product-7', 'Live Chat') ?>
	</aside>
...
```

The parameters to `chat()` are optional, and excluding them will load a one-time chat with
a random UID (e.g. for a one-time site visitor).

## Extending

Conversations are stored and loaded from the database with the `ConversationModel`, and
most of the logic is handled by the Entities. For example, a `Conversation` entity can
`$conversation->addUser($userId)` to join or refresh a user and get back a `Participant`.
A `Participant` can `$participant->say('hello world')` to add a `Message`.
