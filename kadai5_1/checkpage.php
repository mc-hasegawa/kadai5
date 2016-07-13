<?php
header('Content-Type: text/html; charset=UTF-8');
$host = "localhost";
$username = "hasegawa";
$pass = "hasegawa0515";
$dbname = "lesson";
$post_count = 0;
$link = mysql_connect($host, $username, $pass);
$db = mysql_select_db($dbname, $link);
mysql_query('SET NAMES utf8', $link);
session_start();
$input_param_array = $_SESSION["input_param"];
var_dump($input_param_array);
if ($_POST)
{
	foreach ($input_param_array as $value)
	{
		${"input".$post_count} = $value;
		$post_count++;
	}
	$sql = "REPLACE INTO `kadai_hasegawa_ziplist`(`public_group_code`, `zip_code_old`, `zip_code`, `prefecture_kana`, `city_kana`, `town_kana`, `prefecture`, `city`, `town`, `town_double_zip_code`, `town_multi_address`, `town_attach_district`, `zip_code_multi_town`, `update_check`, `update_reason`) VALUES ('$input0','$input1','$input2','$input3','$input4','$input5','$input6','$input7','$input8','$input9','$input10','$input11','$input12','$input13','$input14')";
	$result_flag = mysql_query($sql);
	if (!$result_flag)
	{
	    die('REPLACEクエリーが失敗しました。'.mysql_error());
	}
	$post_count = 0;
	header("Location: ./index.php");
	session_destroy();
	mysql_close($link);
}
?>
<html>
<head>
<meta charset="UTF-8">
<title>PHP課題5_1 確認</title>
</head>
<body>
<p>PHP課題5_1 確認</p>
<p>内容確認</p>
<p>1.全国地方公共団体コード<br><?php echo htmlspecialchars($input_param_array[0]); ?></p>
<p>2.旧郵便番号<br><?php echo htmlspecialchars($input_param_array[1]); ?></p>
<p>3.郵便番号<br><?php echo htmlspecialchars($input_param_array[2]); ?></p>
<p>4.都道府県名(半角カタカナ)<br><?php echo htmlspecialchars($input_param_array[3]); ?></p>
<p>5.市区町村名(半角カタカナ)<br><?php echo htmlspecialchars($input_param_array[4]); ?></p>
<p>6.町域名(半角カタカナ)<br><?php echo htmlspecialchars($input_param_array[5]); ?></p>
<p>7.都道府県名(漢字)<br><?php echo htmlspecialchars($input_param_array[6]); ?></p>
<p>8.市区町村名<br><?php echo htmlspecialchars($input_param_array[7]); ?></p>
<p>9.町域名<br><?php echo htmlspecialchars($input_param_array[8]); ?></p>
<p>10.一町域で複数の郵便番号か<br>
<?php
if(htmlspecialchars($input_param_array[9]) == 0)
{
	echo "該当せず";
}
else
{
	echo "該当";
}
?>
</p>
<p>11.小字毎に番地が起番されている町域か<br>
<?php
if(htmlspecialchars($input_param_array[10]) == 0)
{
	echo "該当せず";
}
else
{
	echo "該当";
}
?>
</p>
<p>12.丁目を有する町域名か<br>
<?php
if(htmlspecialchars($input_param_array[11]) == 0)
{
	echo "該当せず";
}
else
{
	echo "該当";
}
?>
</p>
<p>13.一郵便番号で複数の町域か<br>
<?php
if(htmlspecialchars($input_param_array[12]) == 0)
{
	echo "該当せず";
}
else
{
	echo "該当";
}
?>
</p>
<p>14.更新確認<br>
<?php
switch (htmlspecialchars($input_param_array[13]))
{
	case 0:
		printf("<th>変更なし</th>");
		break;
	case 1:
		printf("<th>変更あり</th>");
		break;
	case 2:
		printf("<th>廃止(廃止データのみ使用)</th>");
		break;
}
?>
</p>
<p>15.更新理由<br>
<?php 
switch (htmlspecialchars($input_param_array[14]))
{
	case 0:
		printf("<th>変更なし</th>");
		break;
	case 1:
		printf("<th>市政・区政・町政・分区・政令指定都市施行</th>");
		break;
	case 2:
		printf("<th>住居表示の実施</th>");
		break;
	case 3:
		printf("<th>区画整理</th>");
		break;
	case 4:
		printf("<th>郵便区調整等</th>");
		break;
	case 5:
		printf("<th>訂正</th>");
		break;
	case 6:
		printf("<th>廃止(廃止データのみ使用)</th>");
		break;
}
?>
</p>
<form name="form_post" method="post">
	<?php
	foreach ($input_param_array as $value)
	{
		printf("<input type='hidden' name='redirect_param_array[%d]' value='%s'>",$post_count,$value);
		$post_count++;
	}
	?>
	<input type="submit" value="登録">
	<input type="submit" value="戻る" onClick="form.action='inputpage.php';return true">
</form>
</body>
</html>