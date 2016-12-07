# domq
Command line HTML DOM Parser

## Install globaly via composer

```bash

composer global require alc/domq

# Make sure you have export PATH in your ~/bashrc
export PATH=~/.config/composer/vendor/bin:$PATH

```

## Usage

```bash

# Query Hacker News Titles
domq https://news.ycombinator.com/ a.storylink innertext

# Query Hacker News Urls
domq https://news.ycombinator.com/ a.storylink getAttribute href

# Query Google Urls
domq https://www.google.fr/search?q=github 'h3.r a' attr href


# Query a local file
domq sitemap.xml 'sitemap loc' innertext

# Query a list of urls
domq urls=urls.txt 'h1.entry-title a' attr href

# Parse from stdin
curl -s http://blog.chemel.fr/sitemap.xml | domq stdin loc innertext

# Piping
domq http://blog.chemel.fr/sitemap.xml loc | domq urls=- loc


# Show help
domq --help

```