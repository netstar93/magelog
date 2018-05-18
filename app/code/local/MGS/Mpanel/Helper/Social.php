<?php
class MGS_Mpanel_Helper_Social extends Mage_Core_Helper_Abstract
{
	public function getTwitterData($tweetUser,$token,$token_secret,$consumer_key,$consumer_secret, $count){
		$host = 'api.twitter.com';
		$method = 'GET';
		$path = '/1.1/statuses/user_timeline.json'; // api call path

		$query = array( // query parameters
			'screen_name' => $tweetUser,
			'count' => $count
		);

		$oauth = array(
			'oauth_consumer_key' => $consumer_key,
			'oauth_token' => $token,
			'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
			'oauth_timestamp' => time(),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_version' => '1.0'
		);

		$oauth = array_map("rawurlencode", $oauth); // must be encoded before sorting
		$query = array_map("rawurlencode", $query);

		$arr = array_merge($oauth, $query); // combine the values THEN sort

		asort($arr); // secondary sort (value)
		ksort($arr); // primary sort (key)

		// http_build_query automatically encodes, but our parameters
		// are already encoded, and must be by this point, so we undo
		// the encoding step
		$querystring = urldecode(http_build_query($arr, '', '&'));

		$url = "https://$host$path";

		// mash everything together for the text to hash
		$base_string = $method."&".rawurlencode($url)."&".rawurlencode($querystring);

		// same with the key
		$key = rawurlencode($consumer_secret)."&".rawurlencode($token_secret);

		// generate the hash
		$signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));

		// this time we're using a normal GET query, and we're only encoding the query params
		// (without the oauth params)
		$url .= "?".http_build_query($query);

		$oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
		ksort($oauth); // probably not necessary, but twitter's demo does it

		// also not necessary, but twitter's demo does this too
		$oauth = array_map(array($this, 'add_quotes'), $oauth);

		// this is the full value of the Authorization line
		$auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));

		// if you're doing post, you need to skip the GET building above
		// and instead supply query parameters to CURLOPT_POSTFIELDS
		$options = array( CURLOPT_HTTPHEADER => array("Authorization: $auth"),
						  //CURLOPT_POSTFIELDS => $postfields,
						  CURLOPT_HEADER => false,
						  CURLOPT_URL => $url,
						  CURLOPT_RETURNTRANSFER => true,
						  CURLOPT_SSL_VERIFYPEER => false);

		// do our business
		$feed = curl_init();
		curl_setopt_array($feed, $options);
		$json = curl_exec($feed);
		curl_close($feed);

		$twitter_data = json_decode($json);
			//echo '<pre>';
			//print_r($twitter_data); die;
		return $twitter_data;
	}
	
	public function add_quotes($str) { 
		return '"'.$str.'"'; 
	}

	// format time
	public function relativeTime($pastTime){
		$origStamp = strtotime($pastTime);					
		$currentStamp = time();		
		$difference = intval(($currentStamp - $origStamp));

		if($difference < 0) 			return false;
		if($difference <= 5)			return $this->__("Just now");
		if($difference <= 20)			return $this->__("Seconds ago");
		if($difference <= 60)			return $this->__("A minute ago");
		if($difference < 3600)			return intval($difference/60).$this->__(" minutes ago");
		if($difference <= 1.5*3600) 		return $this->__("One hour ago");
		if($difference < 23.5*3600)		return round($difference/3600).$this->__(" hours ago");
		if($difference < 1.5*24*3600)           return $this->__("One day ago");
		if($difference < 8640000000)		return  round($difference/86400).$this->__(" days ago");
			
	}
	
	public function relativeTimeUnix($pastTime){
		$origStamp = strtotime($pastTime);					
		$currentStamp = time();		
		$difference = intval(($currentStamp - $origStamp));
		return $difference;
	}
	
	// format string
	public function formatTwitString($strTweet, $truncate){
		$strTweet = preg_replace('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/','<a href="$0" target="_blank">$0</a>',$strTweet);
		$strTweet = preg_replace('/@([a-z0-9_]+)/i', '<a href="http://twitter.com/$1" target="_blank">@$1</a>', $strTweet);
		$strTweet = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<a href="http://search.twitter.com/search?q=%23\2">#\2</a>', $strTweet);

		$strTweet = Mage::helper('core/string')->truncate($strTweet, $truncate);
		
		return $strTweet;
	}
	
	public function getLastTwitter($tweetUser = NULL, $count = NULL, $truncate = NULL){
	
		if($tweetUser == null){
			$tweetUser = Mage::getStoreConfig('social/twitter/twitter_count');
		}

		$token = Mage::getStoreConfig('social/twitter/token');
		$token_secret = Mage::getStoreConfig('social/twitter/token_secret');
		$consumer_key = Mage::getStoreConfig('social/twitter/client_id');
		$consumer_secret = Mage::getStoreConfig('social/twitter/client_secret');
		if($count == null || $count=='' || $count == 0){
			$count = Mage::getStoreConfig('social/twitter/twitter_count');
		}
		
		if($truncate == null || $truncate=='' || $truncate == 0){
			$truncate = Mage::getStoreConfig('social/twitter/truncate');
		}
		
		if($truncate == ''){
			$truncate = 10000;
		}
		
		$twitter_data = $this->getTwitterData($tweetUser,$token,$token_secret,$consumer_key,$consumer_secret, $count);
		
		$twitter_data = json_decode(json_encode($twitter_data), true);
		
		
		$html = '';
		if($token!='' && $token_secret!='' && $consumer_key!='' && $consumer_secret!='' && $tweetUser!=''){
			if(!isset($twitter_data['errors'])){
				try{
					if(count($twitter_data)>0){
						$html .= '<div class="twitter_feed social-tweet">';
						foreach ($twitter_data as $key => $value) {
							$html .= '<div class="tweet-container"><div class="icon"><i class="fa fa-twitter"></i></div><div class="tweet-content"><p>'.$this->formatTwitString($twitter_data[$key]['text'], $truncate).' <a href="https://twitter.com/'.$twitter_data[$key]['user']['screen_name'].'/status/'.$twitter_data[$key]['id_str'].'">about '.$this->relativeTime($twitter_data[$key]['created_at']).'</a></p></div></div>';
						}
						$html .= '</div>';
					}
				}
				catch(Exception $e){
				
				}
			}
		}
		
		
		return $html;
	}
	
	public function checkBlockAccept($block){
		if($block=='facebook'){
			$filePath1 = Mage::getBaseDir() . DS . 'app' . DS . 'design' . DS . 'frontend' . DS . 'base' . DS . 'default' . DS . 'template' . DS . 'mgs' . DS . 'social' . DS . 'facebook-like-box.phtml';
			
			$filePath2 = Mage::getBaseDir() . DS .'app' . DS . 'design' . DS . 'frontend' . DS . Mage::getStoreConfig('mpanel/general/themepack') . DS . 'default' . DS . 'template' . DS . 'mgs' . DS . 'social' . DS . 'facebook-like-box.phtml';
			
			$filePath3 = Mage::getBaseDir() . DS . 'app' . DS . 'design' . DS . 'frontend' . DS . Mage::getStoreConfig('design/package/name') . DS . Mage::getStoreConfig('design/theme/default') . DS . 'template' . DS . 'mgs' . DS . 'social' . DS . 'facebook-like-box.phtml';
			
			if (is_readable($filePath1) || is_readable($filePath3) || is_readable($filePath3)){
				return true;
			}else{
				return false;
			}
		}
		
		if($block=='twitter'){
			$filePath1 = Mage::getBaseDir() . DS . 'app' . DS . 'design' . DS . 'frontend' . DS . 'base' . DS . 'default' . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'twitter_tweets.phtml';
			
			$filePath2 = Mage::getBaseDir() . DS .'app' . DS . 'design' . DS . 'frontend' . DS . Mage::getStoreConfig('mpanel/general/themepack') . DS . 'default' . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'twitter_tweets.phtml';
			
			$filePath3 = Mage::getBaseDir() . DS . 'app' . DS . 'design' . DS . 'frontend' . DS . Mage::getStoreConfig('design/package/name') . DS . Mage::getStoreConfig('design/theme/default') . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'twitter_tweets.phtml';
			
			if (is_readable($filePath1) || is_readable($filePath3) || is_readable($filePath3)){
				return true;
			}else{
				return false;
			}
		}
		
		if($block=='instagram'){
			$filePath1 = Mage::getBaseDir() . DS . 'app' . DS . 'design' . DS . 'frontend' . DS . 'base' . DS . 'default' . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'instagram_widget.phtml';
			
			$filePath2 = Mage::getBaseDir() . DS .'app' . DS . 'design' . DS . 'frontend' . DS . Mage::getStoreConfig('mpanel/general/themepack') . DS . 'default' . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'instagram_widget.phtml';
			
			$filePath3 = Mage::getBaseDir() . DS . 'app' . DS . 'design' . DS . 'frontend' . DS . Mage::getStoreConfig('design/package/name') . DS . Mage::getStoreConfig('design/theme/default') . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'instagram_widget.phtml';
			
			if (is_readable($filePath1) || is_readable($filePath3) || is_readable($filePath3)){
				return true;
			}else{
				return false;
			}
		}
		
		if($block=='flick_photo'){
			$filePath1 = Mage::getBaseDir() . DS . 'app' . DS . 'design' . DS . 'frontend' . DS . 'base' . DS . 'default' . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'flickr_photo.phtml';
			
			$filePath2 = Mage::getBaseDir() . DS .'app' . DS . 'design' . DS . 'frontend' . DS . Mage::getStoreConfig('mpanel/general/themepack') . DS . 'default' . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'flickr_photo.phtml';
			
			$filePath3 = Mage::getBaseDir() . DS . 'app' . DS . 'design' . DS . 'frontend' . DS . Mage::getStoreConfig('design/package/name') . DS . Mage::getStoreConfig('design/theme/default') . DS . 'template' . DS . 'mgs' . DS . 'mpanel' . DS . 'flickr_photo.phtml';
			
			if (is_readable($filePath1) || is_readable($filePath3) || is_readable($filePath3)){
				return true;
			}else{
				return false;
			}
		}
		
		return false;
	}
	
	public function getFlickrData($api_key = NULL, $user_id = NULL) {
		$host = "https://api.flickr.com/services/rest/?";
		$method = "&method=flickr.people.getPublicPhotos";
		$api_key = "&api_key=".$api_key;
		$user_id = "&user_id=".$user_id;
		$format = "&format=json";
		$links = array();
		$link = $host . $method . $api_key . $user_id . $format;
		
		$content = file_get_contents($link);
		$content = str_replace('jsonFlickrApi(','',$content);
		$content = str_replace(')','',$content);
		$content = json_decode($content, true);
		$method_size = "&method=flickr.photos.getSizes";
		$link = $host . $method_size . $api_key . $user_id . $format;
		
		for($i = 0; $i<sizeof($content[photos][photo]); $i++){
			$links[$i] = $link . '&photo_id=' . $content[photos][photo][$i][id];
		}
		for($j=0; $j<$i;$j++){
			$content = file_get_contents($links[$j]);
			$content = str_replace('jsonFlickrApi(','',$content);
			$content = str_replace(')','',$content);
			$content = json_decode($content, true);
			$html = "<a href='".$content[sizes][size][0][url]."' rel='nofollow' target='_blank'><img width='63' height='63' src='".$content[sizes][size][0][source]."' alt='' /></a>";
			echo $html;
		}
	}
	
	public function convertColRow($numberOfProduct) {
        switch ($numberOfProduct) {
            case 1:
                $col = 12;
                break;
            case 2:
                $col = 6;
                break;
            case 3:
                $col = 4;
                break;
            case 4:
                $col = 3;
                break;
            case 5:
                $col = 'custom-5';
                break;
            case 6:
                $col = 2;
                break;
            default:
                $col = 3;
                break;
        }
        return $col;
    }
	
	public function getWidgetFlickrData($api_key = NULL, $user_id = NULL) {
		$host = "https://api.flickr.com/services/rest/?";
		$method = "&method=flickr.people.getPublicPhotos";
		$api_key = "&api_key=".$api_key;
		$user_id = "&user_id=".$user_id;
		$format = "&format=json";
		$links = array();
		$link = $host . $method . $api_key . $user_id . $format;
		
		$content = file_get_contents($link);
		$content = str_replace('jsonFlickrApi(','',$content);
		$content = str_replace(')','',$content);
		$content = json_decode($content, true);
		$method_size = "&method=flickr.photos.getSizes";
		$link = $host . $method_size . $api_key . $user_id . $format;
		
		for($i = 0; $i<sizeof($content[photos][photo]); $i++){
			$links[$i] = $link . '&photo_id=' . $content[photos][photo][$i][id];
		}
		$images = array();
		for($j=0; $j<$i;$j++){
			$content = file_get_contents($links[$j]);
			$content = str_replace('jsonFlickrApi(','',$content);
			$content = str_replace(')','',$content);
			$content = json_decode($content, true);
			$images[$j] = $content[sizes][size][0][source];
		}
		return $images;
	}
	
    public function _iscurl(){
		if(function_exists('curl_version')) {
			return true;
		} else {
			return false;
		}
	}	
	
	public function getInstagramList($access_token) {
		$host = "https://api.instagram.com/v1/users/self/media/recent/?access_token=".$access_token;
		if($this->_iscurl()) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$content = curl_exec($ch);
			curl_close($ch);
		}
		else {
			$content = file_get_contents($host);
		}
		$content = json_decode($content, true);
		return $content;
	}
	
	public function getInstagramData($access_token, $number=NULL, $resolution) {
		$content = $this->getInstagramList($access_token);
		
		if($number == NULL){
			$number = 6;
		}
		$j = 0;
		$i = 0;
		
		foreach($content[data] as $contents){
			$j++;
		}
		if(!$content[data][$i][images][$resolution][url]) {
			echo 'There are not any images in this instagram.';
			return false;
		}
		
		if($number > $j) {
			for($i=0 ; $i<$j; $i++){
				$html = "<a href='".$content['data'][$i]['link']."' rel='nofollow' target='_blank'><img width='".$width."' height='".$height."' src='".$content['data'][$i]['images'][$resolution]['url']."' alt='' /></a>";
				echo $html;
			}
		} else {
			for($i=0 ; $i<$number; $i++){
				$html = "<a href='".$content['data'][$i]['link']."' rel='nofollow' target='_blank'><img width='".$width."' height='".$height."' src='".$content['data'][$i]['images'][$resolution]['url']."' alt='' /></a>";
				echo $html;
			}
		}
	}
	
	public function getWidgetInstagramData($access_token, $count, $resolution) {
		$content = $this->getInstagramList($access_token);
		
		$j = 0;
		$i = 0;
		
		foreach($content[data] as $contents){
			$j++;
		}
		if(!$content[data][$i][images][$resolution][url]) {
			echo 'There are not any images in this instagram.';
			return false;
		}
		$images = array();
		if($count > $j) {
			for($i=0 ; $i<$j; $i++){
				$imagesInfo = array(
					"url" => $content['data'][$i]['link'],
					"like" => $content['data'][$i]['likes']['count'],
					"comment" => $content['data'][$i]['comments']['count'],
					"tags" => $content['data'][$i]['tags'][0],
					"imgUrl" => $content['data'][$i]['images'][$resolution]['url']
				);
				$images[$i] = $imagesInfo;
			}
		} else {
			for($i=0 ; $i<$count; $i++){
				$imagesInfo = array(
					"url" => $content['data'][$i]['link'],
					"like" => $content['data'][$i]['likes']['count'],
					"comment" => $content['data'][$i]['comments']['count'],
					"tags" => $content['data'][$i]['tags'][0],
					"imgUrl" => $content['data'][$i]['images'][$resolution]['url']
				);
				$images[$i] = $imagesInfo;
			}
		}
		return $images;
	}
}