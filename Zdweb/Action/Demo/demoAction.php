<?php
/*
 * Demo程序业务逻辑处理
 */
$dao = new Dao_Demo_DemoDao();//创建DemoDao
$articleInfo = $dao->getArticleInfo();//获取文章信息
$note5Info = $dao->getNote5ByTel();//获取note5信息
