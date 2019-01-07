<?php
$res .= '
<meta charset="[(modx_charset)]" />
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="manifest" href="tpl/manifest.json" />
<meta name="theme-color" content="#ece9e9" />
<link rel="icon" sizes="192x192" href="tpl/img/manifest/logo_x192.png" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
';

$res .= '
<base href="'.MODX_SITE_URL.'" />
';

$uri = substr(MODX_SITE_URL, 0, -1);
$uri .= $_SERVER['REQUEST_URI'];
$res .= '<link rel="canonical" href="'.$uri.'" />
';

$res .= '
<title>[*seo_title*]</title>
<meta name="description" content="[*seo_description*]" />
<meta name="keywords" content="[*seo_keywords*]" />
';

return $res;
