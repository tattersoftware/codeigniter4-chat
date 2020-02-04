# Tatter\Chat

Embedded chat widget for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/chat`
2. Update the database: `> php spark migrate -all`

## Features

**Chat** allows developers to add a lightweight chat client to any page.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/chat`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

Once the files are downloaded and included in the autoload, run any library migrations
to ensure the database is setup correctly:
* `> php spark migrate -all`

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**examples/Chat.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** the library will use its own.

## Usage
