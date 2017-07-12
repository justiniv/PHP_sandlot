<?php

	
	function encode($num)
	{
		if($num >= -8192 || $num <= 8191)
		{
			
			
			$num = 8192 + $num;
			
			
			$hex	=		decimal_to_hex($num);
			
			
			
			
			$lo	=	($num ) & 0x7F;
			
			$hi	=	($num) & 0x3f80;
			
			$encoded	= $lo+ ($hi << 1);
			
			$encoded 	=	decimal_to_hex($encoded);
			
			return  $encoded;
			
		
		}
		else
		{
				$error	= "Integers inserted are out of the -8192....+8191 ranger";
		}
	}
	function array_to_string($array)
	{
		$string 	=	"";
		$max		=	sizeof($array);
		$last		=	$max - 1;
		$separator	=	'';
		foreach( $array as $key)
		{
			$string	.= $key;
			if($key != $last)
			{
				$string	.=	$separator;
			}
		}
		return $string;
	}
	function decimal_to_hex($num)
	{
		
		
		$quotient	=	$num;
		$hex_array	=	array();
		$i			=	0;
		while( $quotient > 0.99)
		{
			$temp 	= $quotient % 16;
			
			
			if($temp < 10)
			{
				$temp	=	$temp +	48;
			}
			elseif($temp > 9)
			{
				$temp	=	$temp + 55;
			}
			
			$temp	= sprintf("%c", $temp);
			$hex_array[$i]	=	$temp;
			$i				=	++$i;
			$quotient		=	$quotient/16;
		}
		
		$hex_array	=	array_reverse($hex_array);
		
		$hex_string	=	array_to_string($hex_array);
		http://ee.hawaii.edu/~tep/EE160/Book/chap4/_27721_table145.gif
		return $hex_string;
	}
	function hex_to_decimal($num)
	{
		$first		=	strlen($num) -1;
		$n			=	0;
		$decimal	=	array();
		
		for($i = $first; $i >= 0; $i--)
		{
			$char	=	 $num[$i] ;
			

			if(($char >= 'a'  || $char <= 'f') )
			{
				$char	=	strtoupper($char);
			}
			
		
			switch ($char) 
			{
				case "A":
				$char	=	10;
				break;
				case "B":
				$char	=	11;
				break;
				case "C":
				$char	=	12;
				break;
				case "D":
				$char	=	13;
				break;
				case "E":
				$char	=	14;
				break;
				case "F":
				$char	=	15;
				break;
				
			}
			
			$decimal[$n]	= $char * (16**$n);
			
			
			$n		=	++$n;

		}

		$answer 	=	array_sum($decimal);
		
		return	$answer;
		
		
	}
	function decode( $hi, $lo)
	{
		$hibyte	=	hex_to_decimal($hi);
		$lobyte	=	hex_to_decimal($lo);
		
		if( ($hibyte	>127 || $hibyte < 0 ) || ($lobyte < 0 || $lobyte > 127))
		{
			$error	= " Your Hexadecimal Variable are Out of the 0x00...0x7F range. <hr/>";
		}
		else
		{
			$hibyte	=	$hibyte	<<7;
			$full	=	$hibyte | $lobyte;
			
			$full	=	$full -8192;
			return $full;
		}
		
	}
	
	
	if(isset($_POST['btn-encode']))
	{
		if($_POST['en-value'] == '')
		{
			$encodedvalue	=	"ERROR!!! Your Input is empty. <hr/>";
		}
		elseif(($_POST['en-value'] > 8191) || ($_POST['en-value'] < -8192 ))
		{
				$encodedvalue	=	"ERROR!!! Your input should remain between the -8192... 8191 range. <hr/>";
		}
		else
		{
			$value			=	$_POST['en-value'];
			
			if(ctype_xdigit ( $value))
			{
				$value			=		hex_to_decimal($value);
				$encodedvalue	=		encode($value);
			}
			elseif(is_int($value))
			{
				$encodedvalue	=		encode($value);
			}
			
			
		}
		
	}
	if(isset($_POST['btn-decode']))
	{
		if(($_POST['de-hi'] == '') && ($_POST['de-lo'] == ''))
		{
			
			$decodedvalue	=	"ERROR!!!  Your input to decode is empty. <hr/>";
		}
		elseif($_POST['de-hi'] == '')
		{
			$decodedvalue	=	"ERROR!!!  Your Hi-byte input is empty. <hr/>";
		}
		elseif($_POST['de-lo'] == '')
		{
			$decodedvalue	=	"ERROR!!!  Your Lo-byte input is empty. <hr/>";
		}
		else
		{
			$hi		=	$_POST['de-hi'];
			$lo		=	$_POST['de-lo'];
			
			if(ctype_xdigit($hi) && ctype_xdigit($lo))
			{
				$decodedvalue	=		decode($hi, $lo);
			}
			elseif(is_int($hi) && is_int($lo))
			{
				$hi				=	decimal_to_hex($hi);
				$lo				=	decimal_to_hex($lo);
				$decodedvalue	=	decode($hi, $lo);
			}
			else
			{
				$decodedvalue	=	"ERROR!!!  Your input text is not valid. <hr/>";
			}
			
		}
		
	}
?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<?php
					if(isset($encodedvalue))
					{
						echo "<br><br><br><br><br><br>". $encodedvalue . "<br><br><br><br><br><br><hr/>";
					}
					elseif(isset($decodedvalue))
					{
						echo "<br><br><br><br><br><br>". $decodedvalue . "<br><br><br><br><br><br><hr/>";
					}
					elseif(isset($error))
					{
						echo "<br><br><br><br><br><br>". $error . "<br><br><br><br><br><br><hr/>";
					}
					elseif(isset($encoded))
					{
						echo "<br><br><br><br><br><br>". $encoded . "<br><br><br><br><br><br><hr/>";
					}
					elseif(isset($full))
					{
						echo "<br><br><br><br><br><br>". $full . "<br><br><br><br><br><br><hr/>";
					}
		?>
		<form method ="POST">
			<br>
			<br>
			<br>
			<br>
			<br>Encode:	<br>
			<input type="text" name="en-value" ><br><br>
			<input type="submit" value="ENCODE" name='btn-encode'><br>
			<hr/>
				

			<br> Decode:<br>
			<label> Hi-byte </label><input type="text" name="de-hi" > <label> Lo-byte </label><input type="text" name="de-lo" ><br><br>
			<input type="submit" value="DECODE" name='btn-decode'>
		</form>
	</body>

</html>