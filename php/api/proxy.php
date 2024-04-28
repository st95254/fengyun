<?php
$targetUrl = 'https://www.allbeauty.com.tw/GoldLeaf/?' . http_build_query($_GET);

// 使用cURL發送請求
$ch = curl_init($targetUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
$result = curl_exec($ch);
curl_close($ch);

// 加載HTML內容到DOMDocument
$dom = new DOMDocument();
@$dom->loadHTML($result); // 使用@來抑制解析時的警告

// 創建XPath
$xpath = new DOMXPath($dom);

// 使用XPath查詢來找到所有價格信息
$priceNodes = $xpath->query("//table[@id='goldleaf']//tr/td[contains(@class, 'right')]");

// 提取價格信息
$prices = [];
foreach ($priceNodes as $node) {
    $prices[] = ['price' => trim($node->nodeValue)]; // 修改此行以在JSON中包含更明確的鍵名
}

// 轉換為JSON並輸出
header('Content-Type: application/json');
echo json_encode($prices);
?>
