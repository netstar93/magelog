<?php

class MGS_Mgscore_Block_System_Config_About extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		try {
    		
    		$feed = curl_init('http://magesolution.com/MGS_Feed.xml');
    		
    		if($feed === false){
    			throw new Exception('Error loading module info feed.'); }
    
    		curl_setopt($feed, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($feed, CURLOPT_HEADER, 0);
    		$xml = curl_exec($feed);
    		curl_close($feed);
    	
    		if($xml === false){
    			throw new Exception('Error loading module info XML.'); }
    	
    		$result = new SimpleXMLElement($xml);
    		
    		if (!$result || !$result->channel->item) {
      			throw new Exception('No info in module info XML.'); }
      			
      			
      		$htmlFeed = curl_init('http://magesolution.com/about/index.html');
      		
      		if($htmlFeed === false){
    			throw new Exception('Error loading about section HTML.'); }
    		
    		curl_setopt($htmlFeed, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($htmlFeed, CURLOPT_HEADER, 0);
    		$html = curl_exec($htmlFeed);
    		curl_close($htmlFeed);
    		
    		if($html === false || $html == ''){
    			throw new Exception('Error loading about section HTML or there is no content.'); }

			if(count($result->channel->item)>0){
				$html.='<div class="grid-extension"><ul>';
				$i=0;
				foreach ($result->channel->item as $item) {
					$i++;
					$html.='<li';
					if($i%5==0){
						$html.=' class="last"';
					}
					$html.='>';
					$html.='<div class="product-img">';
					$html.='<a href="'.$item->link.'" title="'.$item->name.'"><img src="'.$item->img.'" alt="'.$item->name.'"/></a>';
					$html.='</div>';
					$html.='<h4>'.$item->name.'</h4>';
					$html.='<div class="price-container">';
					if(isset($item->old_price)){
						$html.='<span class="old-price">'.$item->old_price.'</span>';
					}
					$html.='<span class="final-price">'.$item->price.'</span>';
					$html.='</div>';
					$html.='</li>';
				}
				$html.='</ul></div>';
			}

    		unset($feed, $xml, $result);
    		
    		return $html;
    	
		} catch (Exception $e) {
      	
      		return $e->getMessage();
  		}
    }
}
