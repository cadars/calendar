<!--
	
	MIT License
	
	Copyright (c) 2021 Neatnik LLC
	
	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all
	copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	SOFTWARE.
	
	--><!DOCTYPE html>
	<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>Calendar</title>
	<meta property="og:title" content="Calendar">
	<meta property="og:url" content="https://neatnik.net/calendar">
	<meta property="og:description" content="A simple printable calendar with the full year on a single page">
  <link rel="stylesheet" href="fonts/terminal-land-mono-sans/stylesheet.css">
  <style>
	* {
	  margin: 0;
    padding: 0;
    box-sizing: border-box;
		color-adjust: exact;
		-webkit-print-color-adjust: exact;
    
    /* Colors */
    --ink:        #111;
    --ink-light:  #777;
    --paper:      #fff;
    
	}
	body {
    color: var(--ink);
    background: var(--paper);
    font-size: 18px;
		line-height: 1.5;
    font-family: 'Terminal Land Mono Sans', Menlo, 'Lucida Grande', system-ui, sans-serif;
    display: flex;
    gap: 4ch;
    padding: 4ch;
	}
  h1 {
    font-size: .85em;
  }
	table {
    width: 23.2em; /* Adjust table width here */
		border-collapse: collapse;
    table-layout: fixed;
    text-align: center;
	}
	th, td {
    color: var(--ink-light);
	}
  th {   
    font-size: .85em;
    font-weight: normal;
    padding-bottom: 1em;
  } 
  td {
    border: 1px dotted transparent;
    padding-top: .3em;
  }
  th:hover {
    color: inherit;
    font-weight: bold;
  }
  table:hover td:not(:empty) {
    border-color: var(--ink-light);
  }
  td:not(:empty):hover {
    background: var(--ink);
    color: var(--paper);
    border-style: solid;
    font-weight: normal;
    cursor: crosshair;
    transform: scale(1.25);
  }
	td:empty {
		border: 0;
	}
	.weekend {
    font-weight: bold;
    color: var(--ink);
	}
	</style>
	</head>
	<body>
	<?php
	date_default_timezone_set('UTC');
	$now = isset($_REQUEST['year']) ? strtotime($_REQUEST['year'].'-01-01') : time();
	$dates = array();
	$month = 1;
	$day = 1;
  
	echo '<h1>'.date('Y', $now).'</h1>';
	echo '<table>';
	echo '<thead>';
	echo '<tr>';
	// Add the month headings
	for($i = 1; $i <= 12; $i++) {
		echo '<th>'.DateTime::createFromFormat('!m', $i)->format('M').'</th>';
	}
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	// Prepare a list of the first weekdays for each month of the year
	$date = strtotime(date('Y', $now).'-01-01');
	$first_weekdays = array();
	
	for($x = 1; $x <= 12; $x++) {
		$first_weekdays[$x] = date('N', strtotime(date('Y', $now).'-'.$x.'-01'));
		$$x = false; // Set a flag for each month so we can track first days below
	}
	
	// Start the loop around 12 months
	while($month <= 12) {
		$day = 1;
		for($x = 1; $x <= 42; $x++) {
			if(!$$month) {
				if($first_weekdays[$month] == $x) {
					$dates[$month][$x] = $day;
					$day++;
					$$month = true;
				}
				else {
					$dates[$month][$x] = 0;
				}
			}
			else {
				// Ensure that we have a valid date
				if($day > cal_days_in_month(CAL_GREGORIAN, $month, date('Y', $now))) {
					$dates[$month][$x] = 0;
					
				}
				else {
					$dates[$month][$x] = $day;
				}
				$day++;
			}
		}
		$month++;
	}
	
	// Now produce the table
	
	$month = 1;
	$day = 1;

	while($day <= 42) {
		echo '<tr>';
		// Start the inner loop around 12 months
		while($month <= 12) {
			if($dates[$month][$day] == 0) {
				echo '<td></td>';
			}
			else {
				
				$date = date('Y', $now).'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($dates[$month][$day], 2, '0', STR_PAD_LEFT);
				if(date('N', strtotime($date)) == '7') {
					echo '<td class="weekend">';
				}
				else {
					echo '<td>';
				}
				echo $dates[$month][$day];
				echo '</td>';
			}
			$month++;
		}
		echo '</tr>';
		$month = 1;
		$day++;
	}
	
	?>
	</tbody>
	</table>
	</body>
	</html>