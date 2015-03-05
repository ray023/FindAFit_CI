<!DOCTYPE html> 
<html> 
	<head> 
		<title>Find A Fit</title> 
		<meta charset="utf-8">
		<meta name="description" content="Find-A-Fit is a free, open source application to finding the closest affiliated gyms." />
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
		<meta name="format-detection" content="telephone=no">
		<?php //Don't allow user to pinch and zoom on browser.  This may not be an optial setting but trying it to fix Barbell calculator issues ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> 
		
                <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
                <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
                <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

	
		<?php header('Content-type: text/html; charset=utf-8');?>
		<?php if (strpos($_SERVER['HTTP_HOST'], 'findafit.info') !== false): ?>
                    <script>
                      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                      ga('create', 'UA-59872717-1', 'auto');
                      ga('send', 'pageview');

                    </script>
		<?php endif; ?>
	</head>
<body>
	<?php $this->load->view($view); ?>
        <script src="<?php echo base_url().'js/jquery.js';?>"></script>
        <script src="<?php echo base_url().'js/bootstrap.js';?>"></script>
</body>
</html>