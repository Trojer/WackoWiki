<?php

if (!defined('IN_WACKO'))
{
	exit;
}

########################################################
##   Pages                                            ##
########################################################

$module['system_statistics'] = array(
		'order'	=> 2,
		'cat'	=> 'Basic functions',
		'status'=> true,
		'mode'	=> 'system_statistics',
		'name'	=> 'Statistics',
		'title'	=> 'Show statistics',
		'vars'	=> array(&$tables, &$directories),

	);

########################################################

function admin_system_statistics(&$engine, &$module)
{
	$order = '';
	$error = '';

	// import passed variables and objects
	$tables			= & $module['vars'][0];
	$directories	= & $module['vars'][1];
?>
	<h1><?php echo $module['title']; ?></h1>
	<br />
	<br />
	Database Statistics:<br />
	<br />
	<table style="max-width:500px" class="formation">
		<tr>
			<th style="width:50px;">Table</th>
			<th style="text-align:left;">Records</th>
			<th style="text-align:left;">Size</th>
			<th style="text-align:left;">Index</th>
			<th style="text-align:left;">Overhead</th>
		</tr>
<?php
	$results	= $engine->load_all("SHOW TABLE STATUS FROM `{$engine->config['database_database']}`", true);
	$tdata		= '';
	$tindex		= '';
	$tfrag		= '';

	foreach ($results as $table)
	{
		foreach ($tables as $wtable)
		{
			if ($table['Name'] == $wtable['name'])
			{
				echo '<tr class="lined">'.
						'<th class="label"><strong>'.$table['Name'].'</strong></th>'.
						'<td>&nbsp;&nbsp;&nbsp;'.number_format($table['Rows'], 0, ',', '.').'</td>'.
						'<td>'.$engine->binary_multiples($table['Data_length'], false, true, true).'</td>'.
						'<td>'.$engine->binary_multiples($table['Index_length'], false, true, true).'</td>'.
						'<td>'.$engine->binary_multiples($table['Data_free'], false, true, true).'</td>'.
					'</tr>'.
					#'<tr class="lined"><td colspan="5"></td></tr>'.
					"\n";

				$tdata	+= $table['Data_length'];
				$tindex	+= $table['Index_length'];
				$tfrag	+= $table['Data_free'];
			}
		}
	}
?>
		<tr class="lined">
			<td class="label"><strong>Total:</strong></td>
			<td></td>
			<td><strong><?php echo $engine->binary_multiples($tdata, false, true, false); ?></strong></td>
			<td><strong><?php echo $engine->binary_multiples($tindex, false, true, false); ?></strong></td>
			<td><strong><?php echo $engine->binary_multiples($tfrag, false, true, false); ?></strong></td>
		</tr>
	</table>
	<br />
	File system Statistics:<br />
	<br />
	<table style="max-width:300px" class="formation">
		<tr>
			<th style="width:50px;">Folder</th>
			<th style="text-align:left;">Files</th>
			<th style="text-align:left;">Size</th>
		</tr>
<?php
	clearstatcache();

	$tfiles = '';
	$tsize = '';

	foreach ($directories as $dir)
	{
		$dir = rtrim($dir, '/');

		if ($handle = @opendir($dir))
		{
			$files	= 0;
			$size	= 0;

			while (false !== ($file = readdir($handle)))
			{
				if (is_dir($dir.'/'.$file) === false)
				{
					$size += filesize($dir.'/'.$file);
					$files++;
					$tfiles++;
				}
			}

			$tsize += $size;

			echo '<tr class="lined">'.
					'<td class="label"><strong>'.$dir.'</strong></td>'.
					'<td>&nbsp;&nbsp;&nbsp;'.$files.'</td>'.
					'<td>'.$engine->binary_multiples($size, false, true, true).'</td>'.
				'</tr>'."\n";
		}

		@closedir($handle);
	}
?>
		<tr class="lined">
			<td class="label"><strong>Total:</strong></td>
			<td>&nbsp;&nbsp;&nbsp;<strong><?php echo $tfiles; ?></strong></td>
			<td><strong><?php echo $engine->binary_multiples($tsize); ?></strong></td>
		</tr>
	</table>

<?php

}

?>