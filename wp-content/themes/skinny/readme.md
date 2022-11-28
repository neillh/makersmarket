<h1 align="center">
  <img src="https://d33v4339jhl8k0.cloudfront.net/docs/assets/53f5a925e4b01c9afd10e8df/images/59a131412c7d3a73488c5307/logo.png" alt="Skinny by Codestag">
</h1>

<p align="center">
  <a href="https://packagist.org/packages/woocommerce/woocommerce"><img src="https://poser.pugx.org/woocommerce/woocommerce/license" alt="license"></a> 
  <a href="https://woocommerce.com/"><img src="http://img.shields.io/badge/Designed%20for-WooCommerce-a46497.svg" alt="Designed for WooCommerce"></a>
</p>

[*Skinny*](https://codestag.com/themes/skinny) is a robust and flexible WordPress theme, designed and built by the team at [Codestag](https://codestag.com/) to help you make the most out of using the [WooCommerce](https://woocommerce.com) plugin to power your online store.

It features deep integration with WooCommerce core plus several of the Codestag plugins.

## Skinny help & support
WooCommerce customers can get support at the [WooCommerce support portal](https://woocommerce.com/contact-us/) otherwise at Codestag Support.
## Contributing to Skinny
If you have a patch, or you've stumbled upon an issue with Skinny, you can contribute this back to the code. Just create a PR with your patch.

## Development Environment

### Table of contents

* [Setting up your environment](#setting-up-your-environment)
	* [Installing development tools](#installing-development-tools)
* [Start development](#development-workflow)
	 * [Run a build](#building-skinny)
* [Linting, standards, etc.](#linting-standards-etc)
	* [Coding standards](#coding-standards)

## Setting up your development environment

### Define `SCRIPT_DEBUG`

When developing locally with Skinny, you should set the `SCRIPT_DEBUG` constant inside of your `wp-config.php` file to `true`. This ensures that all of the unminified files are loaded, since none of the minified js/css files exist in the theme until you run a final production build.

```php
define( 'SCRIPT_DEBUG', true );
```

### Installing development tools

Scripts and styles are compiled using Gulp and a number of node packages. These can be quickly and easily installed using `npm`.

To install all of the Skinny dependencies, you can run the two commands:

```sh
$ npm install
```


## Development workflow

To start work on the Skinny theme you need to follow these steps:

1. [Clone the repository](#clone-the-repository)
2. [Install the development tools](#installing-development-tools)
3. Make sure Skinny is enabled on your WordPress site
4. [Build Skinny](#building-skinny)

## Clone the repository

Make sure you have `git`, `node`, `composer`, and a working WordPress installation.
Clone this repository inside your local install theme directory.

```sh
git@github.com:codestag/skinny.git
cd skinny
```

 You'll need to have a public SSH key setup with GitHub, which is more secure than saving your password in your keychain.
 There are more details about [setting up a public key on GitHub.com](https://help.github.com/en/articles/adding-a-new-ssh-key-to-your-github-account).

## Building Skinny

To work on Skinny you need to build the JavaScript and CSS components of the theme. This will compile all of the scripts and styles into the `/dist` directory.

There are two types of builds:

* ### Development & Production build/Continuous build
	The standard development build will compile necessary JavaScript and CSS files. To build Skinny like this run:

	```sh
	npm run build
	```

	By default the development build above will run once and if you change any of the files, you need to run `npm run build` again to see the changes on the site. If you want to avoid that, you can run a continuous build that will rebuild anytime it sees any changes on your local filesystem. To run it, use:

	```sh
	npm run dev
	```

	To build an installable zip of the theme, use:
	```sh
	npm run build
	```

#### BrowserSync

Skinny leverages BrowserSync to inject styles into the DOM as you are working, so you don't have to continuously refresh the page after making small CSS tweaks.

After running `npm run develop`, anytime you change alter any of the `.css` files in the `.dev/assets/` they will be recompiled and BrowserSync will inject the new styles into the DOM. Our webpack configuration file proxys the requests to `http://skinny.local`. You most likely do not have your site setup at this address locally.

If you are using a different URL, you'll need to update the proxy value inside of the [gulp config file](https://github.com/codestag/skinny/blob/master/gulp.config.js#L12).

> **Important:** Do not commit these changes with your PR.

## Linting, standards, etc.

### Coding standards

We strongly recommend that you install tools to review your code in your IDE of choice. It will make it easier for you to notice any missing documentation or invalid/incorrect coding standards which you should respect. Most IDEs display warnings and notices inside the editor, making it even easier.

- You can find [Code Sniffer rules for WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards#installation) here. Once you've installed these rulesets, you can [follow the instructions here](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards#how-to-use) to configure your IDE.
