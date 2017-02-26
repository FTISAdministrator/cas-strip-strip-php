# CASMinMin (CAS--)
[![Packagist](https://img.shields.io/packagist/v/onlyongunz/cas-min-min-php.svg?style=flat-square)](https://packagist.org/packages/onlyongunz/cas-min-min-php) 
[![Packagist Pre Release](https://img.shields.io/packagist/vpre/onlyongunz/cas-min-min-php.svg?style=flat-square)](https://packagist.org/packages/onlyongunz/cas-min-min-php)
[![GitHub release](https://img.shields.io/github/release/ftis-admin/cas-min-min-php.svg?style=flat-square)](https://github.com/ftis-admin/cas-min-min-php/releases)
[![GitHub tag](https://img.shields.io/github/tag/ftis-admin/cas-min-min-php.svg?style=flat-square)](https://github.com/ftis-admin/cas-min-min-php)

Dibuat untuk membantu kita fetching data dari sitenya UNPAR
yang butuh login terlebih dahulu (CAS).

**Masih dalam pengembangan, belum jalan semestinya**

## Peringatan
Karena ini bukan official dari BTI, mohon di ingat bahwa code ini
tidak selamanya akan bejerja semestinya, mohon buatkan [issue](https://github.com/ftis-admin/cas-min-min-php/issues)nya

## Cara memulai
Gunakan [Composer](https://getcomposer.org/) untuk download package ini... Jangan lupa dump autoloadnya. *Kita pake liblary Guzzle buat fetch datanya.*

```shell
$ composer require onlyongunz/cas-min-min-php
$ composer dumpautoload --optimize
```

lalu tambahkan line ini di kodemu.

```php
include('vendor/autoload.php');
```

## Cara menggunakan

Buat servicenya dulu, baru login-kan pake `CASMinMin::login()`.
```php
use Onlyongunz\CASMinMin;
// buat service
$service = new CASMinMin\Services\StudentPortal();
// buat identity
$identity = new CASMinMin\Identity\NPM('2016730011', 'passwordmu123');

// buat CAS Loginer, lalu lakukan login
$cas = new CASMinMin\CASMinMin($service, $identity);
$cas->login();

// ambil clientnya, dan lakukan fetch sendiri
$service_client = $service->get_client();
```

Sekarang anda dapat menggunakan seluruh fitur dari kelas
`CASMinMin\Services\StudentPortal`.

Dokumentasi lengkap dari sevice-service yang ada akan di tambahkan segera.

## Kontribusi
Dokumen lengkap bisa di cek di [sini](CONTRIBUTING.md).

## Current ToDos
- Support for Services API.