<head>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script>
	$(document).ready(function(){
		// jQuery methodS ...
		$(".yes").click(function(){
			$(".reveal-if-active").show();
		});
		$(".no").click(function(){
			$(".reveal-if-active").hide();
		});		

		$(".non-vac").click(function(){
			$(".second-reveal").show();
		});
		$(".non-vac1").click(function(){
			$(".second-reveal1").show();
		});
		$(".non-vac2").click(function(){
			$(".second-reveal2").show();
		});
		$(".non-vac4").click(function(){
			$(".second-reveal4").show();
		});
		$(".vac").click(function(){
			$(".second-reveal").hide();
		});
		$(".vac1").click(function(){
			$(".second-reveal1").hide();
		});
		$(".vac2").click(function(){
			$(".second-reveal2").hide();
		});
		$(".vac4").click(function(){
			$(".second-reveal4").hide();
		});
		$(".1").click(function(){
			$(".reveal-if-active").show();
		});
		$(".0").click(function(){
			$(".reveal-if-active").hide();
		});		
		$(".applicable_yes").click(function(){
			$(".reveal-if-active").show();
		});
		$(".applicable_no").click(function(){
			$(".reveal-if-active").hide();
		});	
		
		$(".sponsored").click(function(){
			$(".second-reveal3").show();
		});
		$(".not-sponsored").click(function(){
			$(".second-reveal3").hide();
		});
});
	
	</script>
        <style>
		.reveal-if-active, .second-reveal, .second-reveal1, .second-reveal2,.second-reveal3 ,.second-reveal4{display:none;}
		.second-reveal, .second-reveal1, .second-reveal2, .reveal-if-active ,.second-reveal3,.second-reveal4{padding-left:20px;}
		
        </style>
    </head>
