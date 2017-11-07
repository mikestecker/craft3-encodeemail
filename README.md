# Encode Email plugin for Craft CMS 3.x

Protect email addresses in your templates from robots.

This is a quick port of Barrel Strength's [Encode Email](https://sprout.barrelstrengthdesign.com/craft-plugins/encode-email) for Craft 2.x. *This repository will likely be deleted once that version is updated for Craft 3.*


## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

The only way to install this plugin is to add this repository as a dependancy to your project. I don't want to submit this to Packagist since I plan on removing once Barrel Strength publishes their update. *Keep in mind this plugin will not be around long!*

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Open your `composer.json` file and locate the `repositories` section. Add the following:

        {
        	"type": "vcs",
        	"url": "https://github.com/mikestecker/craft3-encodeemail"
        }

3. Then tell Composer to load the plugin:

        composer require mikestecker/craft3-encodeemail

4. In the Control Panel, go to Settings → Plugins and click the “Install” button for Encode Email.

## Encode Email Overview

Encode Email makes multiple email obfuscation filters available in your templates. Currently supported are:

1. encode
2. rot13
3. entities

Note: All filters return raw HTML so make sure you know your data is clean before you use these filters.

## Using Encode Email

### Encode Filter

The `encode` filter will encode your email address and make it harder to be picked up off the page by spambots. Under the hood, the `encode` filter uses the same encoding technique as the Rot13 filter below. `encode` is just an easier name to remember.

### Rot13

The `rot13` filter encodes the string you pass to it using the [Rot13](https://en.wikipedia.org/wiki/ROT13) cipher and returns a javascript tag to decode it on the page.

To use it, you must first create a variable with the entire string you want to encode as a Twig string, and then run the `rot13` filter on the variable you created:

```
{% set email = "<a href='mailto:you@example.com'>Your Name</a>" %}

{{ email | rot13 }}
```

To use this filter for email, your entire link tag must be wrapped in the filter. This is necessary because the filter returns a script tag and will break your link tag if you only include the email address itself. The `rot13` filter will return code similar to the following example in your page code, however, your visitors will see the link tag as you defined it above.

```
<script type="text/javascript">document.write("<n uers=‘znvygb:lbh@rknzcyr.pbz’>Lbhe Anzr</n>".replace(/[a-zA-Z]/g, function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);}));</script>
```

### HTML Entities

The entities filter encodes your content into HTML Entities. If you filter your email information with it like so:

```
<a href='{{ "mailto:you@example.com" | entities }}'>Your Name</a>
```

The code on your page will be output as HTML Entities, but your visitors will see the information that you defined above:

```
<a href='&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#121;&#111;&#117;&#64;&#101;&#120;&#97;&#109;&#112;&#108;&#101;&#46;&#99;&#111;&#109;'>Your Name</a>
```

## Encode Email Roadmap

Some things to do, and ideas for potential features:

* Nothing.

Brought to you by [Mike Stecker](http://github.com/mikestecker)
