<?php
foreach ($data as $row):
	foreach ($row['Shiplist'] as &$cell):
		// Escape double quotation marks
		$cell = '"' . preg_replace('/"/','""',$cell) . '"';
		endforeach;
	echo implode(',', $row['Shiplist']) . "\n";
endforeach;
