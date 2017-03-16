<?php if ($this->editmode) { ?>

<?php 
/*
 * Preselect German language
 * */
if($this->select("streamc_language")->isEmpty()){
	$this->select("streamc_language")->setDataFromResource("de");
}

?>

    <?php // with $this->brick->getPath(); you get the path to the area out of the info-object  ?>
    <link rel="stylesheet" type="text/css" href="<?= $this->brick->getPath(); ?>/editmode.css" />
    <div class="streamchartsEditForm">
        <h2>Streamchartz</h2>
        <div>
            Wall-id:</div>
        <div><?= $this->input("streamc_wallid"); ?>
        </div>               
        <div>
            Key:</div>
        <div> <?= $this->input("streamc_key"); ?>
        </div>
        <div>
            Language: 
        </div>
        <div>    
      <?= $this->select("streamc_language", [
		    "store" => [
		        ["de", "German"],
		        ["en", "English"],
		        ["cs", "Czech"],
		    	["es", "Spanish"],
		    	["fr", "French"],
		    	["it", "Italian"],
		    	["sk", "Slovak"],
		    ]
		]); ?>
        
        </div>
    </div>
<?php } else { 

	?>
    <?php if (!$this->input("streamc_wallid")->isEmpty() && !$this->input("streamc_key")->isEmpty()) { 
	try {        
    	$curl = new Zend_Http_Client_Adapter_Curl();
    	$client = new Zend_Http_Client( 'https://streamchartz.com/fwall/posts', array(
    		'maxredirects' => 0,
    		'timeout'      => 10,
    		'useragent'    => 'streamchartz client pimcore v1.0',
    			
    	));
    	$client->setAdapter( $curl );    	
    	$client->setMethod( Zend_Http_Client::GET );
    	$client->setParameterGet(array(
  			'wall_id' => trim($this->input("streamc_wallid")),
   			'key'=> trim($this->input("streamc_key")),
   			'lang' => $this->select("streamc_language")->getData(),
    	));
    	
    	$response = $client->request();    	
    	
	    	if ($response->getStatus() == 200) {
	    		echo $response->getBody();
	    	} else {
	    		echo "Streamchartz is currently not available.<br />";
	    		echo $response->getStatus() . ": " . $response->getMessage();
	    	}
    	} catch (Exception $e) {
    		echo "Streamchartz is currently not available.<br />Connection problem.";
    	}	
        ?>       
    <?php } ?>
<?php } ?>