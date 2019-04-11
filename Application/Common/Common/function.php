<?php
/*
功能:根据大图片名称放回缩略图
参数:$filename  大图片名称
放回值:缩略图名称
 */
function getSm($filename)
{
	$arr = explode('/',$filename);
	$arr[3] ="sm_".$arr[3];
	return implode('/',$arr);
}
?>
