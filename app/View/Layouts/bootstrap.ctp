<!DOCTYPE html>
<html lang="de">
  <head>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

	<?php
		echo $this->Html->meta('icon');
		
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('glyphicons');
		echo $this->Html->css('custom');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
    	body{ padding-top: 70px; }
    </style>

  </head>

  <body>

    <?php echo $this->Element('navigation'); ?>

    <div class="container-fluid">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
			
    </div><!-- /.container-fluid -->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php
      echo $this->Html->script('jquery-1.11.1.min');
	  echo $this->Html->script('bootstrap.min');
      echo $this->Html->script('bootstrap-typeahead.min.js');
      echo $this->Html->script('trolleyplanung');
    ?>
	
    <script type="text/javascript">
    $(function () {
        $('body').popover({
            selector: '[data-toggle="popover"]'
        });

        $('body').tooltip({
            selector: 'a[rel="tooltip"], [data-toggle="tooltip"]'
        });
    });
  </script>

  </body>
</html>
