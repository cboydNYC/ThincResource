<?php


// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$title.' '.date("d/m/Y").'.csv');
// create a file pointer connected to the output stream
$fp = fopen('php://output', 'w');
// loop over the rows, outputting them
foreach ($rows as $row)
{
	$content= array();
	foreach($fields as $f )
	{
		if($f['download'] =='1'):		
			$limited = isset($field['limited']) ? $field['limited'] :'';
			if(SiteHelpers::filterColumn($limited ))
			{
				$content .= '<td> '. SiteHelpers::formatRows($row->{$f['field']},$f,$row) . '</td>';
			}
		
		endif;	
	}
	fputcsv($fp, $content);
	
}
fclose($fp);
exit;

?>
