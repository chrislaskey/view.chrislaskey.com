<?php

if( !function_exists('ent') ){
	
	function ent($input, $echo = TRUE){
		if( $echo === TRUE ){ echo htmlentities($input, ENT_COMPAT, 'UTF-8'); }
		else{ return htmlentities($input, ENT_COMPAT, 'UTF-8'); }
	}
	
}

if( !function_exists('encode_email_address') ){
	
	function encode_email_address($email, $text = "", $extra = "") {
		preg_match('!^(.*)(\?.*)$!',$email,$match);
		if(!empty($match[2])) {
			return false;
		}
		$address_encode = '';
		for ($x=0; $x < strlen($email); $x++) {
			if(preg_match('!\w!',$email[$x])) {
				$address_encode .= '%' . bin2hex($email[$x]);
			} else {
				$address_encode .= $email[$x];
			}
		}
		if (empty($text))
			$text = $email;
		$text_encode = "";
		for ($x=0; $x < strlen($text); $x++) {
			$text_encode .= '&#x' . bin2hex($text[$x]).';';
		}
		$mailto = "&#109;&#97;&#105;&#108;&#116;&#111;&#58;";
		return '<a href="'.$mailto.$address_encode.'" '.$extra.'>'.$text_encode.'</a>';
	}
	
}

if( !function_exists('create_page_title') ){
	
	function create_page_title($suffix, $divider, $echo = FALSE){
		$CI = &get_instance();
		$uri = array_reverse($CI->uri->segment_array());
		$exceptions = $CI->config->item('page_titles');
		
		foreach( $uri as $key => $val ){
			$val = _clean_page_titles($val);
			if( isset($exceptions) && count($exceptions) > 0 ){
				foreach( $exceptions as $k => $v ){
					$val = str_ireplace($k, $v, $val);
				}unset($k, $v);
			}
			$uri[$key] = $val;
		}
		$title = (count($uri) > 0) ? implode($uri, $divider) . $divider . $suffix : $suffix;
		
		if( $echo === FALSE ){ return $title; }
		else{ echo $title; }
	}
	
}
	
	if( !function_exists('_clean_page_titles') ){
		
		function _clean_page_titles($input){
			return ucwords( trim( strtr( strtr( $input, "_", " " ), "-", " " ) ) );
		}
		
	}

if( !function_exists('create_body_class') ){
	
	function create_body_class($echo = FALSE){
		$CI = &get_instance();
		$uri = $CI->uri->segment_array();
		$class = array();
		
		foreach($uri as $k=>$v){
			$class[] = _clean_body_class($v);
		}
		if( count($class) == 0 ){ $class[] = 'home'; }
		$class = implode($class, ' ');
		
		if( $echo === FALSE ){ return $class; }
		else{ echo $class; }
	}
	
}
	
	if( !function_exists('_clean_body_class') ){
		
		function _clean_body_class($text){
			$text = trim($text);
			$return = '';
			$match = '';
			$count = strlen($text);
			for($i = 0; $i < $count; $i++ ){
				$match = substr($text, $i, 1);
				if( $match == '-' ){ $match = '_'; }
				if( $match == ' ' ){ $match = '_'; }
				if( preg_match('/[a-zA-Z0-9_]/', $match) !== 1 ){ $match = ''; }
				$match = ( substr($return, strlen($return)-1, 1) == '_' && $match == '_') ? '' : $match; //Remove duplicate __'s
				$match = strtolower($match);
				$return .= $match;
			}
			return $return;
		}
		
	}

if( !function_exists('add_trailing_slash') ){
	
	function add_trailing_slash($string){
		if( substr($string, strlen($string)-1, strlen($string)) != '/' ){ return $string . '/'; }
		else{ return $string; }
	}
	
}

if( !function_exists('remove_trailing_slash') ){
	
	function remove_trailing_slash($string){
		if( substr($string, strlen($string)-1, strlen($string)) != '/' ){ return $string; }
		else{ return substr($string, 0, strlen($string)-1); }
	}
	
}

if( !function_exists('show_message') ){
	
	function show_message($echo = TRUE){
		$CI = &get_instance();
		$CI->load->library('session');
		$session = $CI->session->flashdata('message');
		
		if( $session != NULL ){
			if( !isset($session['type']) && count($session) > 1 ){
				$messages = array();
				foreach($session as $s){
					$messages[] = '<div class="message message_type_'.$s['type'].'">'.$s['message'].' <a class="close" href="#">(Close)</a></div>';
				}unset($s);
				$message = implode($messages);
			}else{ $message = '<div class="message message_type_'.$session['type'].'">'.$session['message'].' <a class="close" href="#">(Close)</a></div>'; }
		}else{ $message = null; }
		
		if( $echo === TRUE ){ echo $message; }
		else{ return $message; }
	}
	
}

if( !function_exists('break_at_next_word') ){
	
	function break_at_next_word($limit, $input, $dots = TRUE){
		if( $limit <= strlen( $input ) ){
			$dots = $dots ? " ..." : "";
			return substr( $input, 0, strpos( $input, " ", $limit ) ) . $dots;
		}else{ return $input; }
	}
	
}

if( !function_exists('break_at_length') ){
	
	function break_at_length($limit, $input, $dots = TRUE){
		if( $limit <= strlen( $input ) ){
			$dots = $dots ? "..." : "";
			return substr( $input, 0, $limit ) . $dots;
		}else{ return $input; }
	}
	
}

if( !function_exists('show_nsfw') ){
	
	function show_nsfw(){
		@session_start();
		if( isset($_SESSION['nsfw']) ){
			if( $_SESSION['nsfw'] == 1 ){ return TRUE; }
			else{ return FALSE; }
		}else{ return FALSE; }
	}
	
}

/* From php.net. User comment, http://www.php.net/manual/en/function.array-slice.php#94138 */
if( !function_exists('array_split') ){
	
	function array_split($array, $pieces=2){  
		if ($pieces < 2)
			return array($array);
		$newCount = ceil(count($array)/$pieces);
		$a = array_slice($array, 0, $newCount);
		$b = array_split(array_slice($array, $newCount), $pieces-1);
		return array_merge(array($a),$b);
	}
	
}

/* From php.net. User comment, http://www.php.net/manual/en/function.strip-tags.php#93567 */
if( !function_exists('strip_only') ){
	
	function strip_only($str, $tags) {
		if(!is_array($tags)) {
			$tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
			if(end($tags) == '') array_pop($tags);
		}
		foreach($tags as $tag) $str = preg_replace('#</?'.$tag.'[^>]*>#is', '', $str);
		return $str;
	}
	
}

if( !function_exists('create_slug') ){
	
	function create_slug($text){
		$text = trim($text);
		$slug = '';
		$match = '';
		$count = strlen($text);
		for($i = 0; $i < $count; $i++ ){
			$match = substr($text, $i, 1);
			if( $match == '_' ){ $match = '-'; }
			if( $match == ' ' ){ $match = '-'; }
			if( preg_match('/[a-zA-Z0-9-]/', $match) !== 1 ){ $match = ''; }
			$match = ( substr($slug, strlen($slug)-1, 1) == '-' && $match == '-') ? '' : $match; //Remove duplicate --'s
			$match = strtolower($match);
			$slug .= $match;
		}
		return $slug;
	}
	
}

if( !function_exists('create_file_name') ){
	
	function create_file_name($text){
		$text = trim($text);
		$name = '';
		$match = '';
		$count = strlen($text);
		for($i = 0; $i < $count; $i++ ){
			$match = substr($text, $i, 1);
			if( $match == '-' ){ $match = '_'; }
			if( $match == ' ' ){ $match = '_'; }
			if( preg_match('/[a-zA-Z0-9_]/', $match) !== 1 ){ $match = ''; }
			$match = ( substr($name, strlen($name)-1, 1) == '_' && $match == '_') ? '' : $match; //Remove duplicate __'s
			$match = strtolower($match);
			$name .= $match;
		}
		return $name;
	}
	
}

if( !function_exists('date_time') ){
	
	function date_time($string){
		return date('Y-m-d H:i:s', strtotime($string));
	}
	
}

if( !function_exists('unix_to_date_time') ){
	
	function unix_to_date_time($unix_time){
		return date('Y-m-d H:i:s', $unix_time);
	}
	
}

if( !function_exists('rss_date') ){
	
	function rss_date($string){
		return date('D, d M Y H:i:s T', strtotime($string));
	}
	
}

if( !function_exists('return_time_past') ){
	
	function return_time_past($time, $return_largest = TRUE){
		$time = (!is_numeric($time)) ? strtotime($time) : $time;
		$remainder = @mktime() - $time;
		
		if( $remainder > 31536000 ){
			$years = floor($remainder / 31536000);
			$remainder = $remainder - (31536000 * $years);
			$years = ( $years > 1) ? $years.' years' : $years.' year'; 
		}else{ $years = NULL; }
		
		if( $remainder > 604800 ){
			$weeks = floor($remainder / 604800);
			$remainder = $remainder - (604800 * $weeks);
			$weeks = ( $weeks > 1 ) ? $weeks.' weeks' : $weeks.' week';
		}else{ $weeks = NULL; }
		
		if( $remainder > 86400 ){
			$days = floor($remainder / 86400);
			$remainder = $remainder - (86400 * $days);
			$days = ( $days > 1 ) ? $days.' days' : $days.' day';
		}else{ $days = NULL; }

		if( $remainder > 3600 ){
			$hours = floor($remainder / 3600);
			$remainder = $remainder - (3600 * $hours);
			$hours = ( $hours > 1 ) ? $hours.' hours' : $hours.' hour';
		}else{ $hours = NULL; }

		if( $remainder > 60 ){
			$minutes = floor($remainder / 60);
			$remainder = $remainder - (60 * $minutes);
			$minutes = ( $minutes > 1 ) ? $minutes.' minutes' : $minutes.' minute';
		}else{ $minutes = NULL; }
		
		if( $remainder > 0 ){
			$seconds = ( $remainder > 1 ) ? $remainder.' seconds' : $remainder.' second';
		}else{ $seconds = NULL; }
		
		if( $return_largest === TRUE ){
			if( $years != NULL ){ return $years . ' ago'; }
			elseif( $weeks != NULL ){ return $weeks . ' ago'; }
			elseif( $days != NULL ){ return $days . ' ago'; }
			elseif( $hours != NULL ){ return $hours . ' ago'; }
			elseif( $minutes != NULL ){ return $minutes . ' ago'; }
			elseif( $seconds != NULL ){ return $seconds . ' ago'; }
			else{ return FALSE; }
		}else{
			$return = array();
			if( $years != NULL ){ $return[] = $years; }
			if( $weeks != NULL ){ $return[] = $weeks; }
			if( $days != NULL ){ $return[] = $days; }
			if( $hours != NULL ){ $return[] = $hours; }
			if( $minutes != NULL ){ $return[] = $minutes; }
			if( $seconds != NULL ){ $return[] = $seconds; }
			if( count($return) > 1 ){ $return[(count($return)-1)] = 'and '.$return[(count($return)-1)]; }
			if( count($return) > 0 ){ return implode(', ', $return) . ' ago'; }
			else{ return FALSE; }
		}
		
	}
	
}

if( !function_exists('return_columns') ){
	
	function return_columns($array, $columns = 3){
		
		//Set Variables
		$column = array();
		$total = count($array);
		$per_column = floor($total/$columns);
		$remainder = $total % $columns;
		
		$on_remainder = FALSE;
		$current = 0;
		$i = 1;
		
		//Create Array
		foreach($array as $key => $val ){
			
			$column[$current][$key] = $val;
			
			if( $i >= $per_column ){
				if( $remainder > 0  && $on_remainder === FALSE ){
					$i++;
					$remainder--;
					$on_remainder = TRUE;
				}else{
					$i = 1;
					$current++;
					$on_remainder = FALSE;
				}
			}else{ $i++; }
			
		}
		
		return $column;
		
	}
	
}

if( !function_exists('create_rss') ){
	
	function create_rss($data){
		
		if( is_array($data) ){ $data = (object) $data; }
		if( !is_object($data) ){ return FALSE; }
		
		$items = array();
		foreach($data->items as $k=>$v){
			$items[] = '<item>
							<title>'.$v->title.'</title>
							<link>'.$v->link.'</link>
							<author>celest@cns.bu.edu</author>
							<description>'.htmlentities($v->description).'</description>
							<pubDate>'.$v->pubDate.'</pubDate>
							<guid>'.$v->guid.'</guid>
						</item>';
		}unset($k, $v);
		
		$rss = '<?xml version="1.0"?>
				<rss version="2.0">
					<channel>
						<title>'.$data->title.'</title>
						<link>'.$data->link.'</link>
						<description>'.$data->description.'</description>
						<language>en-us</language>
						<copyright>&copy;'.date('Y', @mktime()).' CELEST</copyright>
						<pubDate>'.date('D, d M Y H:i:s T', @mktime()).'</pubDate>
						<lastBuildDate>'.date('D, d M Y H:i:s T', @mktime()).'</lastBuildDate>
						<docs>http://cyber.law.harvard.edu/rss/rss</docs>
						<generator>CELEST Website RSS Generator</generator>
						<managingEditor>celest@cns.bu.edu</managingEditor>
						<webMaster>celest@cns.bu.edu</webMaster>
						'.implode($items).'
					</channel>
				</rss>';
		
		return $rss;
		
	}
	
}


if( !function_exists('is_mobile') ){
	
	function is_mobile($iphone = TRUE, $ipad = TRUE, $android = TRUE, $opera = TRUE, $blackberry = TRUE, $palm = TRUE, $windows = TRUE, $mobileredirect = FALSE, $desktopredirect = FALSE){
		
		/* Originally published by Andy Moore - .mobi certified mobile web developer - http://andymoore.info/
		 *
		 * This code is free to download and use on non-profit websites, if your website makes a profit or you require support using this code please upgrade.
		 * Please upgrade for use on commercial websites http://detectmobilebrowsers.mobi/?volume=49999
		 *
		 * Modified and stripped down by Chris Laskey
		*/
		
		$mobile_browser	= FALSE; 
		$user_agent		= $_SERVER['HTTP_USER_AGENT'];
		$accept			= $_SERVER['HTTP_ACCEPT'];
		
		switch(TRUE){ 
		
			case (preg_match('/ipad/i',$user_agent)); 
				$mobile_browser = $ipad; 
				$status = 'Apple iPad';
				break; 
				
			case (preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent)); 
				$mobile_browser = $iphone; 
				$status = 'Apple';
				break;
				
			case (preg_match('/android/i',$user_agent));  
				$mobile_browser = $android; 
				$status = 'Android';
				break; 
				
			case (preg_match('/opera mini/i',$user_agent)); 
				$mobile_browser = $opera; 
				$status = 'Opera';
				break; 
				
			case (preg_match('/blackberry/i',$user_agent)); 
				$mobile_browser = $blackberry; 
				$status = 'Blackberry';
				break; 
				
			case (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent)); 
				$mobile_browser = $palm; 
				$status = 'Palm';
				break; 
				
			case (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent)); 
				$mobile_browser = $windows; 
				$status = 'Windows Smartphone';
				break; 
				
			case (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i',$user_agent)); 
				$mobile_browser = TRUE; 
				$status = 'Mobile matched on piped preg_match';
				break; 
				
			case ((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0)); 
				$mobile_browser = TRUE; 
				$status = 'Mobile matched on content accept header';
				break; 
				
			case (isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE'])); 
				$mobile_browser = TRUE; 
				$status = 'Mobile matched on profile headers being set';
				break; 
				
			case (in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',))); 
				$mobile_browser = TRUE; 
				$status = 'Mobile matched on in_array';
				break; 
			
			default;
				$mobile_browser = FALSE; 
				$status = 'Desktop / full capability browser';
				break; 
			
		}
		
		return $mobile_browser;
		
	}

}


/* End of File commonfunctions_helper.php */