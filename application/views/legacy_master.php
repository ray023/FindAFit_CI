<?php	//This is the same as mobile_master but uses version 1.4.3. of jQuery 
		//It has newer features but is not backwards compatible with parts of wod-minder
?>
<!DOCTYPE html> 
<html> 
	<head> 
		<title><?php echo $title; ?></title> 
		<meta charset="utf-8">
		<meta name="description" content="Find A Fit" />
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
		
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.css" />		
		
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script>
		
		<script src="<?php echo base_url().'js/custom.js'. '?' . time();?>"></script>
		<script src="<?php echo base_url().'js/main_functions.js'. '?' . time();?>"></script>
		<?php header('Content-type: text/html; charset=utf-8');?>
	</head>
<body>
	<?php $this->load->view($view); ?>
</body>
</html>