<?php
header('Content-Type: text/html; charset=UTF-8');
$host = "localhost";
$username = "hasegawa";
$pass = "hasegawa0515";
$dbname = "lesson";
$sql = "SELECT * FROM `kadai_hasegawa_ziplist`";
$table_sql = "SHOW FULL COLUMNS FROM `kadai_hasegawa_ziplist`";
require "paging.php";


$link = mysql_connect($host, $username, $pass);
$db = mysql_select_db($dbname, $link);
mysql_query('SET NAMES utf8', $link );

$res = mysql_query($sql);
$column_count = mysql_num_fields($res);

$table_data = mysql_query($table_sql);
$delete_data = array(array());
$count_th = 0;
$count_tr = 0;
if (isset($_POST["checkbox_param"]))
{
	foreach ($_POST["checkbox_param"] as $value)
	{
		$delete_sql = "DELETE FROM `lesson`.`kadai_hasegawa_ziplist` WHERE `kadai_hasegawa_ziplist`.`zip_code` = '$value'";
		if (!$delete_sql_run = mysql_query($delete_sql))
		{
			die("削除失敗");
		}
	}
	header("Location: ./index.php");
}
$RECORD_NUM = 30;	//表示レコード数の定数
$page_num = ceil(mysql_num_rows($res) / $RECORD_NUM);
// var_dump($page_num);
$now_page = 1;
if (isset($_GET["next_page"])) {
	$now_page = $_GET["next_page"];
}

?>
<html>
<head>
<title>PHP課題5_1</title>
</head>
<script>
    function delete_check()
    {
        var delete_flag = confirm("チェックしたレコードを削除してもよろしいですか？");
        console.log(delete_flag);
        return delete_flag;
    }
</script>
<body>
	<p>PHP課題5_1</p>
	<p>
	<?php
	$pageing = new Pagingclass();
	for ($i=$now_page-3; $i <= $now_page; $i++)	//前のページのリンク生成(最大4つまで)
	{ 
		if (1 < $i)
		{
			$pageing->previous_page($i);
		}
	}
	printf("&nbsp;%sページ&nbsp;",$now_page);
	for ($i=$now_page; $i < $page_num; $i++)//次のページのリンク生成(最大4つまで)
	{ 
		if ($now_page+3 < $i)
		{
			break;
		}
		$pageing->following_page($i);
	}
	?>
	</p>
	<form name="table_list" action="" method="post" onsubmit="return delete_check()">
		<p><input type="submit" value="チェック項目の削除"></p>
		<table border=1>
			<?php
			printf("<tr></tr>");
			printf("<th>削除チェック</th>");
			while ($count_th < $column_count)
			{
				$show_table_data = mysql_fetch_assoc($table_data);
				printf("<th>%s</th>", print_r($show_table_data["Comment"],true));
				$count_th++;
			}
			$count_th = 0;
			while($row = mysql_fetch_assoc($res))
			{
				if ($RECORD_NUM*($now_page-1)-1 < $count_tr)
				{
					printf("<tr></tr>");
					$delete_data[$count_tr] = $row["zip_code"];
					printf("<th><input type='checkbox' name='checkbox_param[]' value=$delete_data[$count_tr]></th>");
					while ($count_th < $column_count)
					{
						$column_name = mysql_field_name($res, $count_th);	//カラム名取得
						if($count_th == 3)
						{
							printf("<th><a href='%s?postal_code=%s'>%s</a></th>","overwrite.php",$row[print_r($column_name,true)],$row[print_r($column_name,true)]);
						}
						elseif (9 <= $count_th)
						{
							if ($count_th == 12)
							{
								if ($row[print_r($column_name,true)] == 0)
								{
									printf("<th>該当せず</th>");
								}
								else
								{
									printf("<th>該当</th>");
								}
							}
							else if ($count_th == 13)
							{
								switch ($row[print_r($column_name,true)])
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
							}
							else
							{
								switch ($row[print_r($column_name,true)])
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
							}
						}
						else
						{
							printf("<th>%s</th>", $row[print_r($column_name,true)]);
						}
						$count_th++;
					}
				}
				$count_tr++;
				$count_th = 0;
				if($RECORD_NUM*$now_page == $count_tr){
					break;
				}
			}
			$count_tr = 0;
			?>
		</table>
	</form>
	<p></p>
</body>
<?php mysql_close($link); ?>
</html>