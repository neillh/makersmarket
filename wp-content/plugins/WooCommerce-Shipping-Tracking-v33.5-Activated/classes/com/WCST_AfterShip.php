<?php 
//https://github.com/AfterShip/aftership-sdk-php
require WCST_PLUGIN_ABS_PATH.'/classes/vendor/autoload.php';

class WCST_AfterShip
{
	var $api_key;
	var $courier_slugs = array();
	var $trackings = null;
	var $courier = null;
	var $last_check_point = null;
	public function __construct($key)
	{
		$this->api_key = $key;
		
	}
	private function init_connectors()
	{
		$this->trackings = new AfterShip\Trackings($this->api_key);
		$this->courier = new AfterShip\Couriers($this->api_key);
		$this->last_check_point = new AfterShip\LastCheckPoint($this->api_key);
	}
	public function get_tracking()
	{
		$couriers = new AfterShip\Couriers($key);
		$trackings = new AfterShip\Trackings($key);
		$last_check_point = new AfterShip\LastCheckPoint($key); 
	}
	public function detect_courier_slug_by_tracking_id($tracking_code, $slug = null)
	{
		$this->init_connectors();
		$courier = $this->courier;
		$slug_to_use_for_detection = $slug;
		if(empty($this->courier_slugs) && !isset($slug))
		{
			$complete_courier_list = $courier->all();
			foreach((array)$complete_courier_list["data"]["couriers"] as $courier_data)
				$this->courier_slugs[] = $courier_data["slug"];
				
			$slug_to_use_for_detection =  $this->courier_slugs;
		}
		$slug_to_use_for_detection = isset($slug_to_use_for_detection) && is_array($slug_to_use_for_detection) ?  implode(",", $slug_to_use_for_detection) : $slug_to_use_for_detection;
		$response = $courier->detect($tracking_code, array('slug' => $slug_to_use_for_detection));
		
		return $response;
	}
	public function get_tracking_info($courier_slug, $tracking_code)
	{
		$this->init_connectors();
		$trackings = $this->trackings;
		$tracking_info = [
			'slug'    => $courier_slug,
			
		];
		
	
	
		try
		{
			$response = $trackings->create($tracking_code, $tracking_info);
			$id = $response["data"]["tracking"]["id"];
			$last_check_point = $this->last_check_point;
			$response = $last_check_point->getById($id);
			
		}catch(Exception $e){
			$last_check_point = $this->last_check_point;
			$response = $last_check_point->get($courier_slug, $tracking_code);
			$id = $response["data"]["id"];
			
		}
		
		
		$response = $trackings->getById($id, array('lang' => substr(get_locale(), 0,2)) );
		
		
		return $response;
	}
	public function get_tracking_info_by_tracking_code($tracking_code, $tracking_company_slug = null)
	{
		$tracking_code = trim($tracking_code);
		$skip_detection = false;
		
		if(strpos($tracking_code, "###") !== false)
		{
			$split_result = explode('###', $tracking_code);
			$tracking_company_slug = $split_result[0];
			$tracking_code = $split_result[1];
			$skip_detection = true;
		}
		try
		{
			if(!$skip_detection)
				$courier_slug_detected = $this->detect_courier_slug_by_tracking_id($tracking_code, $tracking_company_slug);
		}catch(Exception $e){return false;}
		
		$response = $error = false;
		if(!$skip_detection && isset($courier_slug_detected["data"]) && $courier_slug_detected["data"]["total"] > 0)
		{
			foreach($courier_slug_detected["data"]["couriers"] as $current_courier)
			{
				try{
					$response = $this->get_tracking_info($current_courier["slug"], $tracking_code);
				}catch(Exception $e){$response = false; $error = true; /* wcst_var_dump($e); */}
				
				if(is_array($response) && !empty($response["data"]["tracking"]["checkpoints"]))
					return $response ;
				elseif(is_array($response) && 
					   isset($response["data"]) && 
					   isset($response["data"]["tracking"]) && 
					   isset($response["data"]["tracking"]["id"]) && 
					   strtolower($response["data"]["tracking"]["tag"] != 'pending')
					   )
				{
					
				}
			}
			
		}
		else if($skip_detection)
		{
			try{
					$response = $this->get_tracking_info($tracking_company_slug, $tracking_code);
				}catch(Exception $e){$response = false; $error = true; }
				
				if(is_array($response) && !empty($response["data"]["tracking"]["checkpoints"]))
					return $response;
		}
		else 
			return "no_currier_detected";
		
		
		return $response;
	}
	public function delete_shipping_tracking_by_id($id)
	{
		//Completely avoid tracking code delete
		return; 
		
		try 
		{
			$this->init_connectors();
			$trackings = $this->trackings;
			$trackings->deleteById($id);
		}catch(Exception $e){$response = false; }
	}
	public function delete_all_tracking_id()
	{
		//Completely avoid tracking code delete
		return;
		
		$this->init_connectors();
		$trackings = $this->trackings;
		
		$result = $trackings->all(/* $options */);
		if(isset($result["data"]) && isset($result["data"]["trackings"]))
			foreach($result["data"]["trackings"] as $tracking)
				$trackings->deleteById($tracking["id"]); 
	}
	public function render_tracking_info_box($params)
	{
		global $wcst_time_model;
		if(!isset($params['tacking_code']) || $params['tacking_code'] == "")
			return "";
		
		$params['preselected_companies'] = isset($params['preselected_companies']) && !empty($params['preselected_companies']) ? $params['preselected_companies'] : null;
		
		$tracking_info = $this->get_tracking_info_by_tracking_code($params['tacking_code'], $params['preselected_companies']);
		
		//Errors
		if($tracking_info == false)
		{
			ob_start();
			echo "<h3>".esc_html__( 'Tracking service unavailable at the moment. Please try again later', 'woocommerce-shipping-tracking' )."</h3>";
			return ob_get_clean();
		}
		else if($tracking_info  == 'no_currier_detected')
		{
			ob_start();
			echo "<h3>".esc_html__( 'No currier detected for the selected tracking code.', 'woocommerce-shipping-tracking' )."</h3>";
			return ob_get_clean();
		}
		$company_slug = $tracking_info["data"]["tracking"]['slug'];
		$status =  strtolower( $tracking_info["data"]["tracking"]["tag"]);
		$tracking_checkpoints =  $tracking_info["data"]["tracking"]["checkpoints"];
		if(empty($tracking_checkpoints))
		{
			ob_start();
			echo "<h3>".esc_html__( 'No tracking info avaliable at the moment. Please try again later', 'woocommerce-shipping-tracking' )."</h3>";
			return ob_get_clean();
		}
		
		
		$counter = 1;
		ob_start();
		if($status == 'pending'):
			echo "<h3>".esc_html__( 'Please try again later, we are awaiting tracking info from the carrier.', 'woocommerce-shipping-tracking' )."</h3>";
		else:
			?>
			<ul class="timeline">
			<?php 
			$tracking_checkpoints = array_reverse($tracking_checkpoints);
			foreach($tracking_checkpoints as $tracking_checkpoint):
					$current_status = strtolower($tracking_checkpoint["tag"]);
			?>
				
				<li class="<?php if($counter++ % 2 == 0) echo 'timeline-inverted'; ?>">
				  <div class="timeline-badge wcst_badge"><img class="wcst_shipping_badge" src="<?php echo WCST_PLUGIN_PATH; ?>/img/aftership/<?php echo $current_status;?>.svg"></img></div>
				  <div class="timeline-panel">
					<div class="timeline-heading">
					  <h4 class="timeline-title"><?php echo $counter - 1; ?>. <?php echo $tracking_checkpoint["message"]; ?></h4>
					  <p><small class="text-muted"><?php echo $wcst_time_model->format_data_according_wordpress_settings ( $tracking_checkpoint["checkpoint_time"] ); //2018-02-24T18:11:10 ?></small></p>
					</div>
					<div class="timeline-body">
					  <p><?php echo $tracking_checkpoint["location"]; ?></p>
					</div>
				  </div>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php 
		endif;
		return ob_get_clean();
	}
}
?>