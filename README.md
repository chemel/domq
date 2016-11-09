# domq
Command line HTML DOM Parser

Install:
======

```bash

composer install

# Create symlink (optional)
ln -s /var/www/domq/domq.php /usr/bin/domq

```

Usage:
======

```bash

# Query Hacker News Titles
php domq.php https://news.ycombinator.com/ a.storylink innertext

# Query Hacker News Urls
php domq.php https://news.ycombinator.com/ a.storylink getAttribute href

# Query Google Urls
php domq.php https://www.google.fr/search?q=github 'h3.r a' getAttribute href

# Query local file
domq sitemap.xml 'sitemap loc' innertext

# Show help
php domq.php --help

```