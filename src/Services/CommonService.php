<?php

namespace App\Services;

class CommonService
{
    private $pdo;

    // コンストラクタでPDOインスタンスを注入
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * 挨拶のメッセージを作る
     * @param $name 挨拶する相手
     * @return $stirng 挨拶のメッセージ
     */
    public function getHelloMsg($name)
    {
        return "Hello, $name";
    }

    /**
     * 暗号化する
     * @param $encryptTxt 暗号化したいテキスト
     * @return $string 暗号化されたテキスト
     */
    public function encrypt($encryptTxt)
    {
        // 暗号化キー
        $encryptKey = $_ENV['ENCRYPT_KEY'];

        // check params
        if (empty($encryptTxt)) {
            return '';
        }

        if (empty($encryptKey)) {
            return '';
        }

        // 暗号化アルゴリズムと初期ベクトルサイズを定義
        /*
            1.  AES: アメリカ国立標準技術研究所（NIST）が標準化した対称鍵暗号方式です。対称鍵暗号とは、暗号化と復号に同じ鍵を使う方式のことです。
                •   AES-128は、128ビットの鍵を使用するAESのバージョンです。他に192ビット、256ビットのバージョンもあります。
            2.  CBCモード（Cipher Block Chaining）: AESの動作モードの一つで、データをブロック単位で暗号化します。
                •   各ブロックは、前の暗号化済みブロックとXOR演算を行った後に暗号化されるため、同じ平文ブロックが繰り返されても異なる暗号文が生成されます。
        */

        // 「cipher（暗号）」は、データや情報を暗号化して読み取れない形に変換する技術や、その暗号自体を指します。
        $cipher = "AES-128-CBC";

        // 「pseudo（擬似、偽の）」は、何かが本物や正式なものに似ているが、完全にはそうではない場合に使われる言葉です。

        // 初期ベクトル（IV: Initialization Vector）が最初のブロックに使われ、セキュリティを強化します。
        // IVは乱数で生成されるため、毎回異なる暗号文が生成されます。
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

        // データを暗号化
        $encrypted = openssl_encrypt($encryptTxt, $cipher, $encryptKey, 0, $iv);

        // 暗号化データと初期ベクトルを統合して返す

        /*
        Base64は、バイナリデータ（例えば画像やファイルの内容）をテキスト形式に変換するためのエンコーディング方式の一つです。具体的には、8ビットバイトデータを6ビット単位に変換し、その6ビットを表す文字（アルファベット大文字、小文字、数字、+, / の64種類）に置き換えて、可読性のある文字列として表現します。
        Base64は、以下のような用途でよく使われます：
            1.  バイナリデータのテキスト化：ネットワークやシステムの制約で、テキストしか扱えない場合にバイナリデータをBase64でエンコードして送信します。
            2.  電子メールの添付ファイル：MIME（Multipurpose Internet Mail Extensions）では、バイナリファイルをBase64でエンコードして送信します。
            3.  画像データの埋め込み：HTMLやCSS内に画像を埋め込む際、Base64形式でエンコードすることで直接書き込むことが可能です。
        エンコード後のデータは、元のバイナリデータよりも約33％大きくなるという特徴がありますが、テキストとして扱えるため、用途によっては便利です。
        */
        return base64_encode($encrypted . '::' . $iv);
    }

    /**
     * 複合化する
     * @param $decryptTxt 複合化したいテキスト
     * @return $string 複合化されたテキスト
     */
    public function decrypt($decryptTxt)
    {
        // 暗号化キー
        $encryptKey = $_ENV['ENCRYPT_KEY'];

        // check params
        if (empty($decryptTxt)) {
            return '';
        }

        if (empty($encryptKey)) {
            return '';
        }

        // 暗号化データを分割（暗号化データと初期ベクトル）
        list($encrtypted_data, $iv) = explode('::', base64_decode($decryptTxt), 2);

        // データの複合化
        return openssl_decrypt($encrtypted_data, "AES-128-CBC", $encryptKey, 0, $iv);
    }
}
