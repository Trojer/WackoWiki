<?php
header('Content-Type: text/html; charset='.$this->get_charset());
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->page['page_lang'] ?>" lang="<?php echo $this->page['page_lang'] ?>">
<head>
<title><?php echo htmlspecialchars($this->config['site_name'], ENT_COMPAT | ENT_HTML401, HTML_ENTITIES_CHARSET).' : '.(isset($this->page['title']) ? $this->page['title'] : $this->tag); ?></title>
<?php // do not index alternative print pages
echo "<meta name=\"robots\" content=\"noindex, nofollow\" />\n";?>
<meta charset=<?php echo $this->get_charset(); ?>" />
<meta name="keywords" content="<?php echo $this->config['meta_keywords'] ?>" />
<meta name="description" content="<?php echo $this->config['meta_description'] ?>" />
<link rel="stylesheet" href="<?php echo $this->config['theme_url'] ?>css/wordprocessor.css" />
</head>

<body>

<div class="header">
<h1><?php echo $this->config['site_name'].':  '.(isset($this->page['title']) ? $this->page['title'] : $this->tag); ?></h1>
</div>