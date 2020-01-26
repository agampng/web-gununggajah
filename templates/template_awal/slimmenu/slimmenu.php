<?php // utf8-marker = äöü 

function tocSlimMenu()
{
	global $hc;
	return(liSlimMenu($hc, 'menulevel'));
}



function liSlimMenu($ta, $st) 
{
	global $s, $l, $h, $cl, $cf, $u;
	$tl = count($ta);
	if ($tl < 1)
		return;
	$t = '';
	$b = 0;
	if ($st > 0) 
	{
		$b = $st - 1;
		$st = 'menulevel';
	}
	$lf = array();
	for ($i = 0; $i < $tl; $i++) 
	{
		$tf = ($s != $ta[$i]);
		
			for ($k = (isset($ta[$i - 1]) ? $l[$ta[$i - 1]] : $b); $k < $l[$ta[$i]]; $k++)
			{
				$t .= "\n" . '<ul';
				if($k == 0)$t.= ' id="navigation" class="slimmenu"';
				$t.= '>' . "\n";
			}
		
		$t .= '<li class="';

		if (!$tf)
			$t .= 's';
		else if (@$cf['menu']['sdoc'] == "parent" && $s > -1) 
		{
			if ($l[$ta[$i]] < $l[$s]) 
			{
				if (@substr($u[$s], 0, strlen($cf['uri']['seperator']) + strlen($u[$ta[$i]])) == $u[$ta[$i]] . $cf['uri']['seperator'])
					$t .= 's';
			}
		}
		$t .= 'doc';
		for ($j = $ta[$i] + 1; $j < $cl; $j++)
			if (!hide($j) && $l[$j] - $l[$ta[$i]] < 2 + $cf['menu']['levelcatch']) 
			{
				if ($l[$j] > $l[$ta[$i]])
					$t .= 's';
				break;
			}
		$t .= '">';
		
		if(!$tf)
		{
			$t.='<a href="#">';
		}
		
		if ($tf)
			$t .= a($ta[$i], '');
		$t .= $h[$ta[$i]];
		
		$t .= '</a>';
		
		if ($st == 'menulevel' || $st == 'sitemaplevel') 
		{
			if ((isset($ta[$i + 1]) ? $l[$ta[$i + 1]] : $b) > $l[$ta[$i]])
				$lf[$l[$ta[$i]]] = true;
			else 
			{
				$t .= '</li>' . "\n";
				$lf[$l[$ta[$i]]] = false;
			}
			for ($k = $l[$ta[$i]]; $k > (isset($ta[$i + 1]) ? $l[$ta[$i + 1]] : $b); $k--) 
			{
				$t .= '</ul>' . "\n";
				if (isset($lf[$k - 1]))
				{
					if ($lf[$k - 1]) 
					{
						$t .= '</li>' . "\n";
						$lf[$k - 1] = false;
					}
				}
			}
		}
		else
		{
			$t .= '</li>' . "\n";
		}
	}
	if ($st == 'submenu' || $st == 'search')
	{
		$t .= '</ul>' . "\n";
	}
	return $t;
}



function slimmenuBottom()
{
	global $pth,$tx;
	
	$smbOutput = '
<script type="text/javascript" src="' . $pth['folder']['template'] . 'slimmenu/jquery.slimmenu.min.js"></script>

<!-- SlimMenu Initialisierung -->
<script type="text/javascript">
$(\'#navigation\').slimmenu(
{
 	initiallyVisible: false,
	resizeWidth: "959",
    collapserTitle: "",
    animSpeed: "fast",
    easingEffect: null,
    indentChildren: true,
	childrenIndenter: "",
	expandIcon: "&#9660;",
	collapseIcon: "&#9650;"
});
</script>
';
	return $smbOutput;
}



function slimmenuBottom2()
{
	global $pth,$tx;
	
	$smbOutput = '
<script type="text/javascript" src="' . $pth['folder']['template'] . 'slimmenu/jquery.slimmenu2.min.js"></script>

<!-- SlimMenu Initialisierung -->
<script type="text/javascript">
$(\'#navigation\').slimmenu(
{
 	initiallyVisible: false,
	resizeWidth: "959",
    collapserTitle: "' . $tx['menu']['main_menu'] . ':",
    animSpeed: "medium",
    easingEffect: null,
    indentChildren: true,
	childrenIndenter: "<span style=\'font-style:normal;font-weight:700;font-size:18px;\'>&raquo;</span>",
	expandIcon: "&#9660;",
	collapseIcon: "&#9650;"
});
</script>
';
	return $smbOutput;
}

?>