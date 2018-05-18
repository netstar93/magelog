<?php
class MGS_Social_Block_Instagram extends Mage_Core_Block_Template {
	
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