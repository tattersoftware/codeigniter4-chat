# Tatter\Chat

Embedded chat widget for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/chat`
2. Update the database: `> php spark migrate -all`
3. Publish the asset files: `> php spark assets:publish`
4. Add a chat to any view: `<?= chat('my-first-chat') ?>`

## Features

**Chat** allows developers to add a lightweight Bootstrap-style chat client to any page.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/chat`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

Once the files are downloaded and included in the autoload, run any library migrations
to ensure the database is setup correctly:
* `> php spark migrate -all`

### Assets

**Chat** has a JS file and a CSS file, as well as dependencies, that need to be included
with any view that has a conversation on it. Included are manifest files to auto-publish
your assets using **Tatter\Assets** with one simple command: `spark assets:publish`. If you
did not install via Composer or you want to publish the assets yourself, move the files from
the library's **assets/** directory into your **public/** directory. You can then include them
in your `<head>` tag or use the **Assets** config file to load them for certain routes:

```
	public $routes = [
		'' => [
			'vendor/bootstrap/bootstrap.min.css',
			'vendor/bootstrap/bootstrap.bundle.min.js',
		],
		'products/show' => [
			'vendor/chat/chat.css',
			'vendor/chat/chat.js',
		],
```

If you install assets manually be sure to include Bootstrap.

### Authentication

**Chat** uses `Tatter\Users` to determine participants username and display name. You must
be sure to include a package that provides `codeigniter4/authentication-implementation`,
like **Myth:Auth** or make your own (see [Authentication](https://codeigniter4.github.io/CodeIgniter4/extending/authentication.html)
for framework requirements).
your own.

## Usage

The easiest way to start a chat is with the helper. Load the helper file (`helper('chat')`)
and then use the `chat($uid, $title)` command wherever you would use a partial view:

```
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
most of the logic is handled by the Entities. For example, use a `Conversation` entity can
`$conversation->addUser($userId)` to join or refresh a user and get back a `Participant`.
A `Participant` can `$participant->say('hello world')` to add a `Message`.
