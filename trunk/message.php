<?php
	include_once('lib/twitese.php');
	$title = 'Direct Messages';
	include_once('inc/header.php');
	include_once('ajax/timeline_format.php');
	
	if (!loginStatus()) header('location: login.php');
?>

<style type="text/css">.timeline li {border-bottom:1px solid #EFEFEF;border-top:none !important}</style>
<script type="text/javascript" src="js/message.js"></script>
<?php 
	$isSentPage = isset($_GET['t'])? true : false;
?>
<div id="statuses" class="column round-left">
	
	<?php include('inc/sentForm.php')?>
	<div id="subnav">
	<?php if ($isSentPage) {?>
       	<span class="subnavLink"><a href="message.php">Inbox</a></span><span class="subnavNormal">Sent</span>
	<?php } else {?>
       	<span class="subnavNormal">Inbox</span><span class="subnavLink"><a href="message.php?t=sent">Sent</a></span>
	<?php } ?>
    </div>

	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		if ($isSentPage) {
			$messages = $t->sentDirectMessage($p);
		} else {
			$messages = $t->directMessages($p);
		}
		if ($messages === false) {
			header('location: error.php');exit();
		} 
		$empty = count($messages) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">No tweets to display.</div>";
		} else {
			$output = '<ol class="timeline" id="allTimeline">';
			
			foreach ($messages as $message) {
				$name = $message->sender_screen_name;
				$imgurl = getAvatar($message->sender->profile_image_url);
				$date = formatDate($message->created_at);
				$text = formatText($message->text);
				
				$output .= "
					<li>
						<span class=\"status_author\">".initShortcutMenu($message->sender)."
							<a href=\"user.php?id=$name\" target=\"_blank\"><img src=\"$imgurl\" title=\"$name\" /></a>
						</span>
						<span class=\"status_body\">
							<span class=\"status_id\">$message->id </span>
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\">$name </a> $text </span>
							<span class=\"actions\">
				";
				
				if (!$isSentPage) {
					$output .= "<a class=\"msg_replie_btn\" href=\"#\">reply</a><a class=\"msg_delete_btn\" href=\"#\">delete</a>";
				} else {
					$output .= "<a class=\"msg_delete_btn\" href=\"#\">delete</a>";
				}
				$output .="</span><span class=\"status_info\">
								<span class=\"date\">$date</span>
						    </span>
						</span>
					</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			
			if ($isSentPage) {
				if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"message.php?t=sent&p=" . ($p-1) . "\">Back</a>";
				if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"message.php?t=sent&p=" . ($p+1) . "\">Next</a>";
			} else {
				if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"message.php?p=" . ($p-1) . "\">Back</a>";
				if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"message.php?p=" . ($p+1) . "\">Next</a>";
			}
			
			$output .= "</div>";
			
			echo $output;
		}
		
		
		
	?>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
