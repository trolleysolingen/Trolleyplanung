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
    <meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />

	<?php
		echo $this->Html->meta('icon');
		
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('jquery.bootstrap-touchspin.min');
		echo $this->Html->css('glyphicons');
		echo $this->Html->css('bootstrap-datetimepicker.min');
		echo $this->Html->css('custom');
		
		echo $this->Html->script('jquery-1.11.1.min');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
		setlocale(LC_TIME, "de_DE");
	?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<link href="//fonts.googleapis.com/css?family=Stalemate:400" rel="stylesheet" type="text/css">

    <style type="text/css">
    	body{
			padding-top: 70px;
		}
    </style>

  </head>

  <body>

    <?php echo $this->Element('navigation'); ?>

    <div class="container-fluid">

			<?php
			
				echo $this->Session->flash(); 
				$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				if (strpos($actual_link,'trolleydemo') !== false) { ?>
				
				<div class="alert alert-info">
					<strong>ACHTUNG!!</strong> Da dies eine Testversion ist, wurde der Email Versand abgeschaltet. In der richtigen Version werden Emails versendet, wenn man einen Verkündiger angelegt hat und ihm die Zugangsdaten zukommen lässt und sich jemand zu seiner Schicht dazugetragen oder gelöscht hat. Die Versammlungsadmins bekommen Mails, wenn Verkündiger Gäste eintragen, die nicht in der Datenbank stehen um zu überprüfen, wer im Versammlungsgebiet Trolleydienst macht.<br/>
					Die Todoliste ist auf den Testsystem inaktiv und kann nur produktiv angesehen werden. Wenn du dennoch sehen willst welche Features für die Zukunft noch geplant sind, schick uns einfach eine Mail.
				</div>
			<?php
				}
			?>

			<?php echo $this->fetch('content'); ?>
			
    </div><!-- /.container-fluid -->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php
	  echo $this->Html->script('bootstrap.min');
	  echo $this->Html->script('jquery.bootstrap-touchspin.min');
	  echo $this->Html->script('moment');
	  echo $this->Html->script('bootstrap-datetimepicker.min');
      echo $this->Html->script('typeahead');
      echo $this->Html->script('responsive-tabs');
      echo $this->Html->script('trolleyplanung');
    ?>
	
    <script type="text/javascript">
    $(function () {

        $('body').tooltip({
            selector: 'a[rel="tooltip"], [data-toggle="tooltip"]'
        });

		$('[data-toggle="popover"]').popover({ 
			html : true, 
			content: function() {
			  return $('#popover_content_wrapper').html();
			}
		});
		
		$(document).on("click", ".open-RouteDialog", function () {
			data = $(this).data('data');
			$('img[id="route"]').attr("src", data)
		});
		
		$(document).on("click", ".open-UploadDialog", function () {
			data = $(this).data('data');
			$('input[id="id"]').attr("value", data)
		});
		
		$('#ConfirmDelete').on('show.bs.modal', function(e) {
			$(this).find('form').attr('action', $(e.relatedTarget).data('action'));
		});
		
		$(document).on("click", ".open-Dialog", function () {
			 var data = $(this).data('data');
			 $(".modal-body #data").html( data );
		});
		
		$('#reportModal').on('show.bs.modal', function(e) {
			$(this).find('form').attr('action', $(e.relatedTarget).data('action'));
		});
		
		$(document).on("click", ".open-ReportDialog", function () {
			 var data = $(this).data('date');
			 $(".modal-body #date").html( data );
			 
			 var data = $(this).data('partner');
			 $(".modal-body #partner").html( data );
		});
		
		// This entire section makes Bootstrap Modals work with iOS
		if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {

		  $('.modal').on('show.bs.modal', function() {
			setTimeout(function () {
			  scrollLocation = $(window).scrollTop();
			  $('.modal')
				  .addClass('modal-ios')
				  .height($(window).height())
				  .css({'margin-top': scrollLocation + 'px'});
			}, 0);
		  });

		  $('input').on('blur', function(){
			setTimeout(function() {
			  // This causes iOS to refresh, fixes problems when virtual keyboard closes
			  $(window).scrollLeft(0);

			  var $focused = $(':focus');
			  // Needed in case user clicks directly from one input to another
			  if(!$focused.is('input')) {
				// Otherwise reset the scoll to the top of the modal
				$(window).scrollTop(scrollLocation);
			  }
			}, 0);
		  })

		}
		
		$("div[class='input-group date']").datetimepicker({
			language: 'de',
			pickTime: false 
		}); 
		
		$("input[id='hours']").TouchSpin({
			min: 0,
			max: 100
		});
		
		$("input[id='minutes']").TouchSpin({
			min: 0,
			max: 59
		});
		
		$("input[id='publishers']").TouchSpin({
			min: 1,
			max: 50
		});
		
		$("input[class='touch-spin']").TouchSpin({
			min: 0,
			max: 100,
			initval: 0
		});
		 			
    });
  </script>

  </body>
</html>
