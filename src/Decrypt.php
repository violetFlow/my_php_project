<?php

namespace App;

class Decrypt
{
    /**
     * 暗号化されたURLを複合化する。
     */
    function decrypt_url($encrypted, $key)
    {
        // 暗号化データを分割（暗号化データと初期ベクトル）
        list($encrtypted_data, $iv) = explode('::', base64_decode($encrypted), 2);

        // データの複合化
        return openssl_decrypt($encrtypted_data, "AES-128-CBC", $key, 0, $iv);
    }
}
