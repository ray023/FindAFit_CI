<!DOCTYPE html> 
<html lang="en">
	<head> 
		<title>Find A Fit</title> 
                <meta name="description" content="Find-A-Fit is a free, open source application to finding the closest affiliated gyms." />
		<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
		<meta name="format-detection" content="telephone=no" />
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
    
    <link href="<?php echo base_url().'css/jumbotron-narrow.css';?>" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</body>
</html>