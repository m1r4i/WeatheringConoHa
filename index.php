<?php
$location = ($_GET["location"] == "")? "Tokyo" : $_GET["location"];
$location = htmlspecialchars($location);
$json = file_get_contents("http://api.weatherapi.com/v1/current.json?key=1c8b53fff94f4cfba1d94743240812&q=$location&aqi=no");
$data = json_decode($json,true);
$degree = $data["current"]["temp_c"];
$weather = $data["current"]["condition"]["icon"];
$humidity = $data["current"]["humidity"];
$wind = $data["current"]["wind_kph"];
$uv = $data["current"]["uv"];

$month = date("n");
$day = date("j");

$conoha_chan = "mini_good.png";

// お正月
if ($month == 1 && $day <= 3) {
    $conoha_chan = "oshogatsu.png";
}
// お雛祭り
elseif ($month == 3 && $day == 3) {
    $conoha_chan = "ohina.png";
}
// 夏の雰囲気
elseif ($month >= 7 && $month <= 8) {
    $conoha_chan = "natsu.png";
}
// ハロウィン
elseif ($month == 10 && $day == 31) {
    $conoha_chan = "haloween.png";
}
// 芸術の秋
elseif ($month == 10 || $month == 11) {
    $conoha_chan = "geijutsu.png";
}
// サンタの期間
elseif ($month == 12 && $day >= 1 && $day <= 25) {
    $conoha_chan = "santa" . rand(0, 2) . ".png";
}
// その他の条件
else {
    if ($degree>= 15 && $degree<= 27) {
        $conoha_chan = "normal.png";
    } elseif ($degree> 27) {
        $conoha_chan = "mini_atsui.png";
    } elseif ($degree< 15) {
        $conoha_chan = "mini_samui.png";
    }
}
$isError = false;
if(empty($weather)){
    $isError = true;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お天気このはちゃん!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="weather-container">
	<div class="weather-info">
        <?php if(!$isError){ ?>
            <div class="temperature">
	    <img src="<?= $weather; ?>" alt="Sunny" class="weather-icon">
	    <p class="temp-value"><?= $degree; ?>℃</p>
            </div>
            <div class="details">
                <div class="detail">
                    <img src="assets/humidity-icon.png" alt="Humidity">
		    <p><?= $humidity; ?>%</p>
                </div>
                <div class="detail">
                    <img src="assets/uv-icon.png" alt="UV Index">
		    <p><?= $uv; ?></p>
                </div>
                <div class="detail">
                    <img src="assets/wind-icon.png" alt="Wind Speed">
		    <p><?= floor(($wind / 60 / 60)*1000); ?>m/s</p>
                </div>
            </div>
            <div class="character-images">
	    <img src="assets/conoha_kawaii/<?= $conoha_chan; ?>" alt="Santa Character" class="character-main">
	    </div>
        <?php } else { ?>
            <div class="character-images">
            <img src="assets/conoha_kawaii/error.png" alt="Santa Character" class="character-main">
            </div>
        <?php } ?>
        </div>

	<footer>
	    <p style="color:#666;" class="location"><span contenteditable="true" id="editable-location"><?php echo $isError? "位置情報が正しくありません。" : $location; ?></span> | <?php echo "更新時刻: ".date("H:i:s"); ?></p>
            <p>このはちゃんイラスト: ©GMO Internet Group, Inc. | 当サイト内の画像の再利用は著作権法により禁止されています。</p>
            <p>利用画像およびガイドライン: <a href="https://conoha.mikumo.com/guideline/" target="_blank">https://conoha.mikumo.com/guideline/</a></p>
            <p><a href="https://www.flaticon.com/free-icons/wind" title="wind icons">Wind icons created by Freepik - Flaticon</a></p>
            <p><a href="https://www.flaticon.com/free-icons/uv" title="uv icons">Uv icons created by Freepik - Flaticon</a></p>
            <p><a href="https://www.flaticon.com/free-icons/humidity" title="humidity icons">Humidity icons created by adriansyah - Flaticon</a></p>
        </footer>
    </div>
    <script> setTimeout(()=>{ location.reload(); },1000*60*20); </script>
<script>
    const locationElement = document.getElementById('editable-location');
    locationElement.addEventListener('focus', (event) => {
        // 編集モードを開始する際にテキストだけを選択
        const text = locationElement.textContent.split(" | ")[0].trim();
        locationElement.textContent = text;
    });

    locationElement.addEventListener('blur', (event) => {
        const newLocation = locationElement.textContent.trim();
        if (newLocation) {
            // URLを編集後の位置情報にリダイレクト
            const newUrl = `${window.location.pathname}?location=${encodeURIComponent(newLocation)}`;
            window.location.href = newUrl;
        }
    });
</script>
</body>
</html>
