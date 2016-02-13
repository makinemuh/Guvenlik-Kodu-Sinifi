# Güvenlik Kodu (Captcha) Sınıfı
Güvenlik kodu (Captcha) sınıfı ile, formlara güvenlik kodu ekleyebilir ve veri girişini güvenli hale getirebiliriz.

## Örnek Kullanımı
Sınıfı kullanmadan evvel, oturumu (session) başlatmış olmanız gerekiyor, aksi halde uygulama hata verecektir. 

image.php:
```php
require 'Captcha.php';
session_start();
$captcha = new Captcha();
return $captcha->image();
```

form.php:
```html
<form method="post" action="validate.php">
  <img src="image.php"><br>
  <input type="text" name="captcha">
  <input type="submit">
</form>
```

validate.php:
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require 'Captcha.php';
  session_start();
  $captcha = new Captcha();
  if ($captcha->validate()) {
    // Güvenlik kodu geçerli
  } else {
    // Güvenlik kodu geçersiz
  }
}
```

## Özel Ayarlar
Sınıfı başlatırken, istersek gösterilecek resimdeki karakterlerin sayısını ve hangi karakterleri göstereceğini belirleyebiliyoruz. Şöyle ki
```php
$length = 10;
$chars = 'ABCDEFG1234567890';
$captcha = new Captcha($length, $chars);
```
Resimi gösterirkense, resmin boyutunu ve fontun boyutunu ayarlayabiliyoruz.
```php
$size = [250, 80];
$font = 3;
return $captcha->image($size, $font);
```
Font boyutunu en fazla 5 yapabiliyoruz.
