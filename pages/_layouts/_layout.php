<?php
$pageKey = isset($pageKey)?$pageKey:null;

$pageTitle = isset($pageTitle)?$pageTitle:'Clicpresse';

$pageDescription = isset($pageDescription)?$pageDescription:"";

$pageKeywords = isset($pageKeywords)?$pageKeywords:"";

$pageOgTitle = isset($pageOgTitle)?$pageOgTitle:$pageTitle;
$pageOgType = isset($pageOgType)?$pageOgType:'website';
$pageOgUrl = isset($pageOgUrl)?$pageOgUrl:null;
$pageOgImage = isset($pageOgImage)?$pageOgImage:URL::absolute('assets/img/logo.png');
$pageOgImageType = isset($pageOgImageType)?$pageOgImageType:'';
$pageOgSiteName = isset($pageOgSiteName)?$pageOgSiteName:Config::get('sitename');
$pageOgDescription = isset($pageOgDescription)?$pageOgDescription:$pageDescription;

?>