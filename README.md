# Catstat

A companion site for [Lynxsie's](https://www.twitch.tv/lynxsiethewarmest) cat
collector chat minigame in 2 parts -- a serverside app (in `site`) and a
clientside syncing script (meant to be ran by the streamer, in `sync_script`).

**Still very much in its initial stages of development!! Doesn't really do much yet lmao!!**

## Dev setup (server)

The server side requires PHP and [Composer](https://getcomposer.org/).

1. `cd site`
2. `composer update` to install packages
3. `cd public` then `php -S localhost:8080` to start dev server

## Setup (script)

The client side requires Python and [uv](https://docs.astral.sh/uv/).

1. `cd sync_script`
2. copy `.env.example` to `.env` and set values accordingly
3. `uv run main.py` to install packages and run
