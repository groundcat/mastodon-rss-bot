# mastodon-rss-bot

## Getting Started

This bot retrieves the latest item from the RSS/Atom feed and post it to your Mastodon account automatically. You can run the bot in your website hosting panel or a VPS under the php environment. 

## Prerequisites

php5.6+

This file needs to work with rss-php, which is a small and easy-to-use library for consuming RSS and Atom feeds. You can download rss-php [here](https://github.com/dg/rss-php).

## Configuration

Follow these steps:

1. Put `bot.php` in the same directory of [rss-php](https://github.com/dg/rss-php).
2. Edit the configuration area in `bot.php` with the token of your Mastodon bot account, URL of your instance, and the URL of RSS or Atom feed.
3. Create an empty file named `storage.txt` in the same directory.
4. Set the permission of `storage.txt` file to be writable.
5. Test running `bot.php` to see if it works.
6. Create a crontab to frequently run `bot.php`, for example, every 20 minutes.

## Author

- Author's Website: https://yanziyao.cn
- Author's Mastodon: https://o3o.ca/@salt

## License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details.