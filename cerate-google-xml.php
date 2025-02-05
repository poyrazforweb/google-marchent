<?php

try {
    require_once 'inc/connect.php'; // veritabanı dosaynızı bağlayabilirsiniz $db değişkeninde değilse toplu düzeltme ile kendi yapınıza göre düzenleyin 

    if (!$db) {
        throw new Exception("Veritabanı bağlantısı başarısız!");
    }

    $xmlFile = "raxgame-urunler.xml";

    $xml = new DOMDocument("1.0", "UTF-8");
    $xml->formatOutput = true;

    $rss = $xml->createElement("rss");
    $rss->setAttribute("xmlns:g", "http://base.google.com/ns/1.0");
    $rss->setAttribute("version", "2.0");
    $xml->appendChild($rss);

    $channel = $xml->createElement("channel");
    $rss->appendChild($channel);

    $title = $xml->createElement("title", "RaxGame Ürünleri");
    $link = $xml->createElement("link", "https://raxgame.com");
    $description = $xml->createElement("description", "RaxGame dijital ürünleri");

    $channel->appendChild($title);
    $channel->appendChild($link);
    $channel->appendChild($description);

    $query = "SELECT id, urun_code, title, img, price, seo_url, seo_description FROM epin_list WHERE status = 1";
    $result = $db->query($query);

    if (!$result) {
        throw new Exception("Sorgu hatası: " . print_r($db->errorInfo(), true));
    }

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $item = $xml->createElement("item");

        /** "./" karakterlerini kaldır ve tam URL oluştur */
        $cleanImgPath = ltrim($row['img'], './'); 
        $fullImageURL = "https://raxgame.com/" . $cleanImgPath;

        $safe_title = preg_replace('/[\x00-\x1F\x7F]/', '', $row['title']);
        $safe_description = preg_replace('/[\x00-\x1F\x7F]/', '', $row['seo_description']);

        $id = $xml->createElement("g:id", htmlspecialchars($row['id']));
        $title = $xml->createElement("g:title");
        $title->appendChild($xml->createCDATASection($safe_title));
        $img = $xml->createElement("g:image_link", htmlspecialchars($fullImageURL));
        $description = $xml->createElement("g:description");
        $description->appendChild($xml->createCDATASection($safe_description));

        /** Fiyat sadece TL olacak şekilde formatlandı */
        $price = $xml->createElement("g:price", htmlspecialchars(number_format($row['price'], 2, '.', '') . " TRY"));

        $seo_url = $xml->createElement("g:link", "https://raxgame.com/epin-urun/" . htmlspecialchars($row['seo_url']));
        $stock = $xml->createElement("g:availability", "in_stock");
        $condition = $xml->createElement("g:condition", "new");
        $brand = $xml->createElement("g:brand", "RaxGame");

        /** GTIN olmayan dijital ürünler için identifier_exists false olarak ayarlandı */
        $identifier_exists = $xml->createElement("g:identifier_exists", "false");

        /** Ürünün dijital olduğunu belirten alan */
        $product_type = $xml->createElement("g:product_type", "Dijital Ürün");

        /** Google kategorisi sadece 'Software' olarak değiştirildi */
        $category = $xml->createElement("g:google_product_category", "Software");

        /** Rusya'ya satış yapılmaması için */
        $excluded_destination = $xml->createElement("g:excluded_destination", "RU");

        $item->appendChild($id);
        $item->appendChild($title);
        $item->appendChild($img);
        $item->appendChild($description);
        $item->appendChild($price);
        $item->appendChild($seo_url);
        $item->appendChild($stock);
        $item->appendChild($condition);
        $item->appendChild($brand);
        $item->appendChild($identifier_exists);
        $item->appendChild($product_type);
        $item->appendChild($category);
        $item->appendChild($excluded_destination);

        $channel->appendChild($item);
    }

    if (!$xml->save($xmlFile)) {
        throw new Exception("XML dosyası kaydedilemedi!");
    }

    header("Content-Type: application/xml; charset=UTF-8");
    echo $xml->saveXML();
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>
