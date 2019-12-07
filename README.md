<p align="center">
    <a href="https://yai.ac.id" target="_blank">
        <img src="./web/images/logo_lpt_yai.png">
    </a>
    <h1 align="center">Sistem Pendukung Keputusan Kepala Program Studi Universitas Y.A.I</h1>
    <br>
</p>

Sistem Pendukung Keputsan Kepala Program Studi [Universitas Y.A.I](https://yai.ac.id) merupakan aplikasi yang dibuat oleh saya untuk menyelesaikan program studi Teknik Informatika saya.

Aplikasi ini bertujuan untuk membantu kepala program studi dalam monitoring segala kebutuhan linkungan belajar mengajar kampus. Mulai dari kinerja Mahasiswa, grafis jumlah mahasiswa tiap tahun, dan lain lain.

Ini merupakan bagian sistem dari aplikasi sistem pendukung keputusan kepala program studi universitas Y.A.I yang bertujuan sebagai RESTAPI Server nya.

[![Latest Stable Version](https://img.shields.io/packagist/v/samdgea/sipk-kaprodi-api.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/samdgea/sipk-kaprodi-api.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Build Status](https://travis-ci.org/samdgea/sipk-kaprodi-api.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-basic)

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources
      widgets/            contains widget resources


REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install via Git


You can then install this project using the following command:

~~~
git clone https://github.com/samdgea/sipk-kaprodi-api.git
~~~

Then navigate to project folder using your system console, and type the following command:

~~~
php yii serve
~~~

This command as default will open web server on host 127.0.0.1 and on port 8080, you can define it by adding host:port after serve command.

Then, open your favourite browser this link

~~~
http://127.0.0.1:8080
~~~

CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run -- --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit -- --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit -- --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
