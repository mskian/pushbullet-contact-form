# Pushbullet Contact Form

PHP Contact Form with Pushbullet Notification 📨

<p><img src="https://raw.githubusercontent.com/mskian/pushbullet-contact-form/master/screenshot.png" width="836" height="636"></p>

- Bootstrap4
- ParsleyJS Form Validation
- PHP cURL
- CSRF Token
- Pushbullet API (For Sending the User Submit Notification to Pushbullet)

### Usage

- Recommended PHP Version 7 or Greater Version
- Composer (For Install the `phpdotenv` Package)
- Install package

```bash
composer install
```

- Create `env` file

```bash
touch .env
```

- Add the API URL and API KEY on `.env` File

```
APIURL = "https://api.pushbullet.com/v2/pushes"
APIKEY = "YOUR PUSHBULLET ACCOUNT API KEY"
```

- Test From Localhost

```bash
php -S localhost:8080
```

## LICENSE

MIT

