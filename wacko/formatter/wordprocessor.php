<?php

if (!defined('IN_WACKO'))
{
	exit;
}

$text = preg_replace('/{{(toc).*?}}/i', '', $text);
$data = $this->format($text, 'wiki');
$data = preg_replace('/<br\s*>/', '</p><p>', $data);

echo $data;