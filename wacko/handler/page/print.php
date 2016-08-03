<?php

if (!defined('IN_WACKO'))
{
	exit;
}

echo ADD_NO_DIV . '<article class="page">';
$include_tail = '</article>';

$this->ensure_page();

if (!$this->has_access('read'))
{
	$this->set_message($this->_t('ReadAccessDenied'), 'error');
	$this->show_must_go_on();
}

/* obsolete code - or do we need an ability to print old revisions?
if ($this->page['latest'] == 0)
{
	echo "<div class=\"revisioninfo\">".
	str_replace('%1',$this->href(),
	str_replace('%2',$this->tag,
	str_replace('%3',$this->page['modified'],
	$this->_t('Revision')))).".</div>";
}*/

if ((($user = $this->get_user()))? $user['numerate_links'] : $this->db->numerate_links)
{
	// start enumerating links
	$this->numerate_links = [];
}

// build html body
$data = $this->format($this->page['body'], 'wacko');

// display page
$data = $this->format($data, 'post_wacko', array('bad' => 'good'));
$data = $this->numerate_toc($data); //  numerate toc if needed
echo $data;

// display comments
if (@$this->sess->show_comments[$this->page['page_id']] || $this->forum)
{
	if (($comments = $this->load_comments($this->page['page_id'])))
	{
		echo '<br /><br />';
		echo '<section id="comments">';
		echo '<header class="header-comments">';
		echo $this->_t('Comments_all');
		echo "</header>\n";

		foreach ($comments as $comment)
		{
			if (!$comment['body_r'])
			{
				$comment['body_r'] = $this->format($comment['body']);
			}

			echo '<article class="comment">' .
					'<span class="comment-info">' .
						'<strong>&#8212; ' . $this->user_link($comment['user_name']) . '</strong> (' .
						$this->get_time_formatted($comment['created']) .
						($comment['modified'] != $comment['created'] ? ', ' . $this->_t('CommentEdited') . ' ' .
						$this->get_time_formatted($comment['modified']) : '') . ')'.
					'&nbsp;&nbsp;&nbsp;</span><br />' .
					$this->format($comment['body_r'], 'post_wacko') .
				"</article>\n";
		}
		echo "</section>\n";
	}
}

// numerated links
if ($this->numerate_links)
{
	if (!isset($comments)) echo '<br />';

	echo '<br />';
	echo '<section id="links">';
	echo '<header class="linksheader">';
	echo $this->_t('Links');
	echo "</header>\n";

	$i = 0;

	foreach ($this->numerate_links as $l => $n)
	{
		if ($i++)
		{
			echo "<br /><br />\n";
		}

		echo '<span class="reflink"><sup>' . $n. '</sup> ' . $l . "</span>\n";
	}

	echo "</section>\n";
}

// stop enumerating links
$this->numerate_links = null;
