<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Example;
use App\Encrypt;
use App\Decrypt;

$example = new Example();
echo $example->sayHello();
echo "<br>";

// URLを暗号化するデータとキーを定義
$url = "https://example.com/coupons?user=hahaha716";
$key = "mysecretkey12345"; // 16文字のキー

// URL暗号化
$encrypt = new Encrypt();
$encrypted_url = $encrypt->encrypt_url($url, $key);
echo "暗号化されたURL: " . $encrypted_url;
echo "<br>";

// URL複合化
$decrypt = new Decrypt();
$decrypted_url = $decrypt->decrypt_url($encrypted_url, $key);
echo "複合化されたURL: " . $decrypted_url;
echo "<br>";

?>