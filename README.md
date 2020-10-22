# php-project-lvl2

[![Maintainability](https://api.codeclimate.com/v1/badges/0519a0a207c0a0bca831/maintainability)](https://codeclimate.com/github/earthrobot/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/0519a0a207c0a0bca831/test_coverage)](https://codeclimate.com/github/earthrobot/php-project-lvl2/test_coverage)
[![Github Actions Status](https://github.com/earthrobot/php-project-lvl2/workflows/hex1-workflow/badge.svg)](https://github.com/earthrobot/php-project-lvl2/actions)
[![asciicast](https://asciinema.org/a/Z035IfG5tpl2565lWb873BXD2.svg)](https://asciinema.org/a/Z035IfG5tpl2565lWb873BXD2)
[![asciicast](https://asciinema.org/a/Jse4VY0COsaITR1cwqHPIjNVW.svg)](https://asciinema.org/a/Jse4VY0COsaITR1cwqHPIjNVW)
[![asciicast](https://asciinema.org/a/5mqP36VME9t6MF96jVM3MZJgA.svg)](https://asciinema.org/a/5mqP36VME9t6MF96jVM3MZJgA)
[![asciicast](https://asciinema.org/a/h8gigAH1U9aA2j4jYhJFbARAN.svg)](https://asciinema.org/a/h8gigAH1U9aA2j4jYhJFbARAN)
[![asciicast](https://asciinema.org/a/rfBFbj041Biq9gDN1BC8hOgxK.svg)](https://asciinema.org/a/rfBFbj041Biq9gDN1BC8hOgxK)

## Setup
```sh
$ composer require marina/hex2
```

## Run tests
```sh
$ make test
```

## Formats

-   pretty
-   plain
-   json

## Example

```sh
$ php bin/gendiff file1.json file2.json

{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
```

```sh
$ gendiff --format plain file1.yml file2.yml

Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to [complex value]
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From 'too much' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]
```
