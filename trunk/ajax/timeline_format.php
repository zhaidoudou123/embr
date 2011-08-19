﻿<?php
	function format_retweet($status, $retweetByMe = false){
		$retweeter = $status->user;
		$rt_status = $status->retweeted_status;
		$status_owner = $rt_status->user;
		$date = $status->created_at;
		$text = formatText($rt_status->text);
		$html = '<li>
			<span class="status_author">
			<a href="user.php?id='.$status_owner->screen_name.'" target="_blank"><img src="'.getAvatar($status_owner->profile_image_url).'" title="click for more..." /></a>
			</span>
			<span class="status_body">
			<span title="Retweets from people you follow appear in your timeline." class="big-retweet-icon"></span>
			<span class="status_id">'.$status->id_str.'</span>
			<span class="status_word"><a class="user_name" href="user.php?id='.$status_owner->screen_name.'">'.$status_owner->screen_name.'</a> <span class="tweet">&nbsp;'.$text.'</span></span>';
		$html .= recoverShortens($text);
		$html .= '<span class="actions">
			<a class="replie_btn" title="Reply" href="#">Reply</a>
			<a class="rt_btn" title="Retweet" href="#">Retweet</a>';
		if($retweetByMe != true){
			$html .= '<a class="retw_btn" title="New Retweet" href="#">New Retweet</a>';
		}
		$html .= '<a class="favor_btn" title="Favorite" href="#">Favorite</a>
					<a class="trans_btn" title="Translate" href="#">Translate</a>';
		if($retweetByMe == true){
			$html .= '<a class="delete_btn" title="Delete" href="#"><span class="rt_id" style="display: none;">'.$status->id_str.'</span></a>';
		}
		$html .='</span>
			<span class="status_info"><span class="source">Retweeted by <a href="user.php?id='.$retweeter->screen_name.'">'.$retweeter->screen_name.'</a> via '.$status->source.'</span>
			<span class="date"><a href="status.php?id='.$rt_status->id_str.'" title="'.date('Y-m-d H:i:s', strtotime($status->created_at)).'" target="_blank">'.$date.'</a></span>
			</span>
			</span>
			</li>';
		return $html;
	}

	function format_retweet_of_me($status){
		$status_owner = $status->user;
		$date = $status->created_at;
		$text = formatText($status->text);
		$html = '<li>
			<span class="status_author">
			<a href="user.php?id='.$status_owner->screen_name.'" target="_blank"><img src="'.getAvatar($status_owner->profile_image_url).'" title="click for more..." /></a>
			</span>
			<span class="status_body">
			<span title="Retweets from people you follow appear in your timeline." class="big-retweet-icon"></span><span class="status_id">'.$status->id_str.'</span>
			<span class="status_word">
			<a class="user_name" href="user.php?id='.$status_owner->screen_name.'">'.$status_owner->screen_name.'</a><span class="tweet">&nbsp;'.$text.'</span></span>';
		$html .= recoverShortens($text);
		$html .= '<span class="actions">
			<a class="replie_btn" title="Reply" href="#">Reply</a>
			<a class="rt_btn" title="Retweet" href="#">Retweet</a>
			<a class="favor_btn" title="Favorite" href="#">Favorite</a>
			<a class="trans_btn" title="Translate" href="#">Translate</a>
			<a class="delete_btn" title="Delete" href="#">Delete</a>
			</span>
			<span class="status_info">via '.$status->source.'
			<span class="date"><a href="status.php?id='.$status->id_str.'" title="'.date('Y-m-d H:i:s', strtotime($status->created_at)).'" target="_blank">'.$date.'</a></span>
			</span>
			</span>
			</li>';
		return $html;
	}

	function getRetweeters($id, $count = 20){
		$t = getTwitter();
		$retweeters = $t->getRetweeters($id);
		$html = '<span class="vcard">';
		foreach($retweeters as $retweeter){
			$user = $retweeter->user;
			$html .= '<a class="url" title="'.$user->name.'" rel="contact" href="../user.php?id='.$user->screen_name.'">
				<img class="photo fn" width="24" height="24" src="'.getAvatar($user->profile_image_url).'" alt="'.$user->name.'" />
				</a>';
		}
		$html .= "</span>";
		return $html;
	}

	// $updateStatus 标识是否为发推, 是则应用指定 css
	function format_timeline($status, $screen_name, $updateStatus = false){
		$user = $status->user;
		$date = $status->created_at;
		$text = formatText($status->text);

		if(preg_match('/^\@'.getTwitter()->username.'/i', $text) == 1){
			$output = "<li class=\"reply\">";
		}elseif($updateStatus == true){
			$output = "<li class=\"mine\">";
		}else{
			$output = "<li>";
		}

		$output .= "<span class=\"status_author\">
			<a href=\"user.php?id=$user->screen_name\" target=\"_blank\"><img src=\"".getAvatar($user->profile_image_url)."\" title=\"Click for more...\" /></a>
			</span>
			<span class=\"status_body\">
			<span class=\"status_id\">$status->id_str </span>
			<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$user->screen_name\">$user->screen_name</a><span class=\"tweet\"> $text </span></span>";
		$output .= recoverShortens($text);
		$output .= "<span class=\"actions\">
			<a class=\"replie_btn\" title=\"Reply\" href=\"#\">Reply</a>
			<a class=\"rt_btn\" title=\"Retweet\" href=\"#\">Retweet</a>";
		if($user->screen_name != $screen_name){
			$output .= "<a class=\"retw_btn\" title=\"New Retweet\" href=\"#\">New Retweet</a>";
		}
		$output .= "<a class=\"favor_btn\" title=\"Favorite\" href=\"#\">Favorite</a>
					<a class=\"trans_btn\" title=\"Translate\" href=\"#\">Translate</a>";
		if ($user->screen_name == $screen_name) $output .= "<a class=\"delete_btn\" title=\"Delete\" href=\"#\">Delete</a>";
		$output .= "</span><span class=\"status_info\">";
		if ($status->in_reply_to_status_id) $output .= "<span class=\"in_reply_to\"> <a class=\"ajax_reply\" href=\"ajax/status.php?id=$status->in_reply_to_status_id_str&uid=$user->id \">in reply to $status->in_reply_to_screen_name</a>&nbsp;</span>";
		$output .= "<span class=\"source\">via $status->source</span>
			<span class=\"date\"><a href=\"status.php?id=$status->id_str\" title=\"".date('Y-m-d H:i:s', strtotime($status->created_at))."\" target=\"_blank\">$date</a></span>
			</span>
			</span>
			</li>";
		return $output;
	}
?>
