<?php

/***************************
  Sample using a PHP array
****************************/
/***
if (@!$_POST['submit_it']) {
	?>
	<style>
		.input-caption {
			text-align:right;
			padding: 6px;
		}

		.input-container {
		  position: relative;
 		 	width: 150px;
		}

		.input-container input {
			width: 100%;
		}
		.input-container .unit {
			position: absolute;
			display: block;
			top: 3px;
			right: -3px;
			background-color: grey;
			color: #ffffff;
			padding-left: 5px;
			width: 45px;
		}
	</style>

	<div style="font-size:1.6em;">Testing BFSS, Assessor Starter Form<br><br></div>

	<form method="POST">
	<div class="input-container">	<div class="input-container"><div><span class="input-caption">Full Name:</span>				<input type="text" name="FULL_NAME_TOP" placeholder="Full Name" value="Joe Blow In Idaho"><span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Age:</span>						<div class="input-container"><input type="text" name="AGE" placeholder="Age" value="51"> y/o<span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Sport:</span>					<div class="input-container"><input type="text" name="SPORT" placeholder="Sport" value="Rugbee"><span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Weight:</span>					<div class="input-container"><input type="text" name="WEIGHT" placeholder="Weight" value="112"> lbs<span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Sex:</span>						<div class="input-container"><input type="text" name="SEX" placeholder="Sex" value="chick"><span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Reactive:</span>				<div class="input-container"><input type="text" name="YOU_REACTIVE" placeholder="Reactive" value="22"> in<span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Elastic:</span>					<div class="input-container"><input type="text" name="YOU_ELASTIC" placeholder="Elastic" value=""> in<span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Ballistic:</span>				<div class="input-container"><input type="text" name="YOU_BALLISTIC" placeholder="Ballistic" value="44.5"> in<span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Acceleration:</span>			<div class="input-container"><input type="text" name="YOU_ACCELERATION" placeholder="Acceleration" value="66"> secs<span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Maximal:</span>					<div class="input-container"><input type="text" name="YOU_MAXIMAL" placeholder="Maximal" value="777"> lbs<span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Elite Reactive:</span>		<div class="input-container"><input type="text" name="ELITE_REACTIVE" placeholder="Elite Reactive" value="88"> in<span class="unit">Volts</span></div></div>
		<div><span class="input-caption">Benchmark Reactive:</span>	<div class="input-container"><input type="text" name="BENCHMARK_REACTIVE" placeholder="Benchmark Reactive" value="99.9"> in<span class="unit">Volts</span></div></div>

		<div><br><br><input type="submit" name="submit_it" style="font-weight:bold;" value="Save Data / Generate PDF"></div>
	</form>
	<?php
}
else {
***/
	error_reporting(0);
  require('fpdm_start.php');

  require('FillForm.php');

	$pdf = new FPDM('../pdf_templates/starter-report-pdftk.pdf');

  $fillForm = new FillPdf;

  $postAry = $_POST;
  $postAry['FULL_NAME_TOP'] = 'Jilly Bean';

  $fillArray = $fillForm->getPostArray('starter-report.pdf', $postAry);

	// $fields = array(
	// 	'FULL_NAME_TOP'    => @$_POST['FULL_NAME_TOP'],
	// 	'AGE'    => @$_POST['AGE'] . ' y/o',
	// 	'SPORT'    => @$_POST['SPORT'] . ' ',
	// 	'WEIGHT'    => @$_POST['WEIGHT'] . ' lbs',
	// 	'SEX'    => @$_POST['SEX'] . ' ',
	// 	'YOU_REACTIVE'    => ' ' . @$_POST['YOU_REACTIVE'] . ' in',
	// 	'YOU_ELASTIC'    => ' ' . @$_POST['YOU_ELASTIC'] . ' in',
	// 	'YOU_BALLISTIC'    => ' ' . @$_POST['YOU_BALLISTIC'] . ' in',
	// 	'YOU_ACCELERATION'    => ' ' . @$_POST['YOU_ACCELERATION'] . ' secs',
	// 	'YOU_MAXIMAL'    => ' ' . @$_POST['YOU_MAXIMAL'] . ' lbs',
	// 	'ELITE_REACTIVE'    => ' ' . @$_POST['ELITE_REACTIVE'] . ' in',
	// 	'BENCHMARK_REACTIVE'    => ' ' . @$_POST['BENCHMARK_REACTIVE'] . ' in',
	// );

	$pdf->Load($fillArray, false); // second parameter: false if field values are in ISO-8859-1, true if UTF-8
	$pdf->Merge();
  $pdf->Output();

// }

?>
