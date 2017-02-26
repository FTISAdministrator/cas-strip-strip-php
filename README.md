# CASMinMin (CAS--)
Dibuat untuk membantu kita fetching data dari sitenya UNPAR
yang butuh login terlebih dahulu (CAS).

**Masih dalam pengembangan, belum jalan semestinya**

## Peringatan
Karena ini bukan official dari BTI, mohon di ingat bahwa code ini
tidak selamanya akan bejerja semestinya, mohon buatkan [issue](https://github.com/ftis-admin/cas-min-min-php/issues)nya

## Cara memulai
Untuk sementara, repositori ini masih harus di clone dulu.

```shell
$ git clone https://github.com/ftis-admin/cas-min-min-php
```

Selanjutnya anda hanya tinggal panggil
[Composer](https://getcomposer.org/) untuk install dependency
dan dump autoloadya. *Kita pake liblary Guzzle buat fetch datanya.*

```shell
$ composer install
$ composer dumpautoload --optimize
```

lalu tambahkan line ini di kodemu.

```php
include('vendor/autoload.php');
```

## Cara menggunakan

Buat servicenya dulu, baru login-kan pake `CASMinMin::do_login()`.
```php
use Onlyongunz\CASMinMin;
// buat service
$service = new CASMinMin\Services\StudentPortal();
// buat identity
$identity = new CASMinMin\Identity\NPM('2016730011', 'passwordmu123');

// buat CAS Loginer, lalu lakukan login
$cas = new CASMinMin\CASMinMin($service);
$cas->do_login($identity);
```

Sekarang anda dapat menggunakan seluruh fitur dari kelas
`CASMinMin\Services\StudentPortal`.

Dokumentasi lengkap dari sevice-service yang ada akan di tambahkan segera.

## Kontribusi
Dokumen lengkap bisa di cek di [sini](CONTRIBUTING.md).

## Current ToDos
- Service Base
- Login with CAS
- Support for Services API.