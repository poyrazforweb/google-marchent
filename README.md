# XML Ürün Akışı Oluşturma

Bu PHP betiği, bir veritabanından ürünleri çekerek XML formatında bir besleme dosyası oluşturur. Google Merchant Center gibi hizmetlerde kullanılabilir.

## Gereksinimler

- PHP 7.4 veya daha yeni bir sürüm
- MySQL veya MariaDB destekli bir veritabanı
- PDO eklentisi etkinleştirilmiş olmalıdır

## Kurulum

1. Proje dizinine bu dosyayı ekleyin.
2. `inc/connect.php` dosyanızda veritabanı bağlantı bilgilerini tanımlayın.
3. Sunucunuzda hata ayıklama için `error_reporting(E_ALL);` ve `ini_set('display_errors', 1);` etkinleştirilmiştir. Canlı ortamda kapatmanız önerilir.

## Çalışma Mantığı

1. **Veritabanı Bağlantısı**: `connect.php` dosyası üzerinden veritabanına bağlanır.
2. **XML Dosyası Oluşturma**: `DOMDocument` kullanılarak XML belgesi oluşturulur.
3. **Ürünleri Çekme**: `epin_list` tablosundan `status = 1` olan ürünler çekilir.
4. **Verilerin Formatlanması**:
   - Başlık ve açıklama özel karakterlerden arındırılır.
   - Resim URL’si düzenlenir.
   - Fiyat TL formatına uygun şekilde düzenlenir.
   - Google Merchant Center için gerekli etiketler eklenir.
5. **XML Dosyasının Kaydedilmesi**: Dosya, belirtilen adla kaydedilir.

## Önemli Notlar

- **Güvenlik**: Hata mesajlarını son kullanıcıya göstermek yerine log dosyasına yazdırmanız önerilir.
- **Veri Tutarlılığı**: Veritabanındaki verilerin eksiksiz ve doğru formatta olduğundan emin olun.
- **Ürün Kategorisi**: Google Merchant Center için `Software` olarak belirlenmiştir.
- **Dil Desteği**: XML çıktısı UTF-8 formatında oluşturulmaktadır.

## Kullanım

Bu dosyayı bir tarayıcıda çalıştırarak veya bir cronjob ile belirli aralıklarla çalıştırarak güncel XML beslemesi oluşturabilirsiniz.

```sh
php script.php
```

Alternatif olarak, tarayıcı üzerinden çalıştırabilirsiniz:
```
https://siteniz.com/script.php
```

## Hata Ayıklama

Hata alırsanız, aşağıdaki adımları kontrol edin:

1. **Veritabanı Bağlantısı**: `connect.php` dosyanızda doğru bilgiler tanımlandığından emin olun.
2. **Dosya Yazma İzinleri**: XML dosyanızın kaydedildiği dizinde yazma izinlerinin uygun olduğundan emin olun.
3. **PHP Logları**: Sunucu hata loglarını kontrol edin.

## Lisans

Bu proje açık kaynak olarak sunulmaktadır. Dilediğiniz gibi kullanabilir ve geliştirebilirsiniz.

