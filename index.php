<html>
	<head>
		
	</head>
	<body>
		<?php
			echo 'Hello World!<br>';

			$number = 12345 * 6789;
			echo 'Substr: '.substr($number, 3, 1) . '<br>';

			$pi = "3.142";
			$radius = 5;
			echo 'Area of a circle with radius ' . $radius . ' is '. $pi * ($radius * $radius) . '<br>';

			function longdate($timestamp){
				return date("l F jS y", $timestamp);
			}
			echo 'Todays date: ' .longdate(time()) . '<br>';
			echo '23 years ago at a time like this: ' . longdate((time() - (23 * 365 * 24 *60 *60))) . '<br>';

			function test_static(){
				static $count =0;
				echo $count . '<br>' ;
				$count ++;
			}
			test_static();
			test_static();
			test_static();

		?>



	</body>
</html>