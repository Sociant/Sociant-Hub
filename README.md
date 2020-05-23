![Analyze your account with Sociant Hub](readme-header.png "Sociant Hub Header")

# Sociant Hub

Sociant Hub is an mini project for tracking and analyzing Twitter Followers on top of [Symfony](https://symfony.com/).

This project was created originally for private use to test the still existing twitter features. Due to some requests I worked on the project as a public accessible website, [Sociant Hub](https://hub.sociant.de).

This project uses [TwitterOAuth](https://github.com/abraham/twitteroauth) by [abraham](https://github.com/abraham) for requests and is based on Symfony 5.0.

# Installation

To install Sociant Hub for your own use make sure you use PHP 7.4, a [MySQL Database](https://mysql.com/) or higher and have [Composer](http://packagist.org/) installed.

### 1. Clone the repository

Clone with HTTPS: https://github.com/Sociant/Sociant-Hub.git

Clone with SSH: git@github.com:Sociant/Sociant-Hub.git

### 2. Install dependencies with composer

Run `composer require`

### 3. Configure the application

Copy the contents of the given `.env` file into a new file called `.env.local`. To operate Sociant Hub properly you should fill out every given field.

Field | Description | Values | Example
---  | --- | --- | ---
APP_ENV | Symfony Enviroment | `dev` or `prod` | dev
APP_SECRET | Random generated secret || 4Tuepn6UZkasRLqEbshLC2RX
MAILER_DSN | DSN Settings for E-Mail delivery || Please consider checking out the [Documentation](https://symfony.com/doc/current/mailer.html)
TWITTER_API_KEY | Public Twitter API Key || Please visit [Twitter Developer](https://developer.twitter.com/en/apps) for obtaining the key pair
TWITTER_API_SECRET_KEY | Secret Twitter API Key || Read above
TWITTER_CALLBACK_URL | Return URL after successful authentication || Read above
REPATCHA_PUBLIC | Public Recaptcha Key for Contact Page || Please visit [Recaptcha Admin](https://www.google.com/recaptcha/admi) for obtaining the key pair
REPATCHA_SECRET | Secret Recaptcha Key for Contact Page ||  Read above
RECIPIENT_EMAIL | Recipient E-Mail for Contact Page || john@doe.com

### 4. Run Symfony

You can either run symfony via console with `symfony server:start` or run it via Apache or Nginx. Visit [Configuring a Web Server](https://symfony.com/doc/current/setup/web_server_configuration.html) for more information.

### 5. That's it

Now you can Login from your given URL or local Port to visit your instance.

To automate requests you can add a cronjob for fetching the data automatically.

Example for Web Service in `/var/www/html` every 5 Minutes: 

```*/5 * * * * php /var/www/html/bin/console app:update-user 1>> /dev/null 2>&1```

Or you can update your data manually in the dashboard or for every user with `php bin/console app:update-user`.

Please consider that due to API limitations you can only update every user once per hour.

# Used Plugins

* [TwitterOAuth](https://github.com/abraham/twitteroauth) by [abraham](https://github.com/abraham)

# License

Sociant Hub is released under the [Apache-2.0](license.md) License

# Authors

Sociant Hub was created by [l9cgv](https://twitter.com/l9cgv) as Project for [SociantWD](https://twitter.com/SociantWD).

Thanks to [DeZio91](https://twitter.com/DeZio91) and [Marcel Malberg](https://twitter.com/TheCrealm) for testing out the application in beta state.