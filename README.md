# Web Application Template

A minimal and extendable web (PHP and Vue) application template.

## Quick usage

1. Download this repository.

2. Install dependencies:

   ```bash
   composer install
   npm install
   ```

3. Copy `app/bootstrap/config.php.dist` to `app/bootstrap/config.php`.

4. Start webpack in "watch" or "build" mode:

   ```
   npm run watch
   ```

   ```
   npm run build
   ```

## Notes and tips

### Deploy

This template proposes to use really nice [PHPloy](https://github.com/banago/PHPloy)
as a deployment tool. If you use another deployment tool or method you can simply delete
`phploy.ini` from the repository root.

### Remove www, force www, force https

You can find all the rules in `public_html/.htaccess` file. Just uncomment what you need.

## Credits

[Yuri Plashenkov](https://plashenkov.com)

## License

This project is licensed under the [MIT license](LICENSE.md).
