<div class="pageBefore">&nbsp;</div>
<div class="page">
<?php

// redirect to show method if page don't exists
if (!$this->page) $this->Redirect($this->href("show"));

if ($this->HasAccess("read"))
{
	if (!$this->page)
	{
		print(str_replace("%1",$this->href("edit"),$this->GetResourceValue("DoesNotExists")));
	}
	else
	{
		// comment header?
		if ($this->page["comment_on"])
		{
			print("<div class=\"commentinfo\">".$this->GetResourceValue("ThisIsCommentOn")." ".$this->ComposeLinkToPage($this->page["comment_on"], "", "", 0).", ".$this->GetResourceValue("PostedBy")." ".($this->IsWikiName($this->page["user"])?$this->Link($this->page["user"]):$this->page["user"])." ".$this->GetResourceValue("At")." ".$this->page["time"]."</div>");
		}

		if ($this->page["latest"] == "N")
		{
			print("<div class=\"revisioninfo\">".
			str_replace("%1",$this->href(),
			str_replace("%2",$this->GetPageTag(),
			str_replace("%3",$this->page["time"],
			$this->GetResourceValue("Revision")))).".</div>");
		}

		// display page
		$this->context[++$this->current_context] = $this->tag;
		$data = $this->Format($this->page["body"], "wakka");
		if ($this->tocAutoNumerate == 1) $data = $this->tocEnumerate($data,2); // TOC, Automatic numeration of headings
		print($data);
		$this->current_context--;

	}
}
else
{
	print($this->GetResourceValue("ReadAccessDenied"));
}
?>
</div>
