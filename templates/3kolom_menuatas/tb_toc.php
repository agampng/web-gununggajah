<?php
/*
This file is part of a template, which was created by Torsten Behrens.
Take a modern CMSimple XH version. www.cmsimple-xh.org www.cmsimple.name www.cmsimple.me www.cmsimple.eu

Version 08.08.2013. Update for jQuery4CMSimple.
Version 12.02.2014. Joining of tb_toc.php, tb_toc_h.php and tb_toc_v.php.
##################################################################################
# Torsten Behrens                                                                #
# Dorfstraße 2                                                                   #
# D-24619 Tarbek                                                                 #
# USt.ID-Nr. DE214080613                                                         #
# http://torsten-behrens.de                                                      #
# http://tbis.info                                                               #
# http://tbis.net                                                                #
# http://cmsimple-templates.de                                                   #
# http://cmsimple-templates.com                                                  #
##################################################################################
*/
?>
<?php
/*
These are modified version of the toc()function of Peter Harteg's CMSimple
(www.cmsimple.dk). It has been modified by Nikolai Bock and Christoph M. Becker
for Torsten Behrens (www.torsten-behrens.de).

It is called via the template.htm file. You call it by adding at the top of the
template.htm file:
  <?php include ($pth['folder']['template'].'tb_toc.php'); ?>

The tb_toc.php file is placed in the folder where the template resides. In the
template you do not use "toc()" but "tb_toc()" (for "simple" menus) or
"tb_li($hc, 'menulevel')" (for drop-down menus) as menu function. Use "tb_li_h"
or "tb_li_v" instead of "tb_li" as appropriate.
*/

function tb_toc($start = NULL, $end = NULL, $li = 'tb_li', $prefix = 'art')
{
	global $c, $cl, $s, $l, $cf;
	$ta = array();
	if (isset($start)) {
		if (!isset($end))$end = $start;
	}
	else $start = 1;
	if (!isset($end))$end = $cf['menu']['levels'];
	$ta = array();
	if ($s > -1) {
		$tl = $l[$s];
		for($i = $s; $i > -1; $i--) {
			if ($l[$i] <= $tl && $l[$i] >= $start && $l[$i] <= $end)if(!hide($i))$ta[] = $i;
			if ($l[$i] < $tl)$tl = $l[$i];
		}
		@sort($ta);
		$tl = $l[$s];
	}
	else $tl = 0;
	$tl += 1+$cf['menu']['levelcatch'];
	for($i = $s+1; $i < $cl; $i++) {
		if ($l[$i] <= $tl && $l[$i] >= $start && $l[$i] <= $end)if(!hide($i))$ta[] = $i;
		if ($l[$i] < $tl)$tl = $l[$i];
	}
	return call_user_func($li, $ta, $start, $prefix);
}

/**
 * Generic li function. Do not use directly.
 */
function tb_li_hv($ta, $st, $class, $classifyLi)
{
    global $s, $l, $h, $cl, $cf, $u, $adm, $edit, $pd_router;

    $tl = count($ta);
    if ($tl < 1)return;
    $t = '';
    if ($st == 'submenu' || $st == 'search')$t .= '<ul class="'.$st."\n".'">';
    $b = 0;
    if ($st > 0) {
        $b = $st-1;
        $st = 'menulevel';
    }
    $lf = array();
    for($i = 0; $i < $tl; $i++) {
        $tf = ($s != $ta[$i]);
        if ($st == 'menulevel' || $st == 'sitemaplevel') {
            for($k = (isset($ta[$i-1])?$l[$ta[$i-1]]:$b); $k < $l[$ta[$i]]; $k++)
                if($k==0)
                    $t .= "\n<ul class=\"$class\">\n";
                else
                    $t .= "\n".'<ul class="active">'."\n";
        }
        $t .= '<li class="';
        if (!$tf && $classifyLi) {
            $t .= 'active';
        }
        $isActive = false;
        if (!$tf)$isActive = true;
        else if(@$cf['menu']['sdoc'] == "parent" && $s > -1) {
            if ($l[$ta[$i]] < $l[$s]) {
                if (@substr($u[$s], 0, 1+strlen($u[$ta[$i]])) == $u[$ta[$i]].$cf['uri']['seperator']){
                    $t .= 'active';
                    $isActive = true;
                }
            }
        }
        //$t .= 'doc';
        /*for($j = $ta[$i]+1; $j < $cl; $j++)if(!hide($j) && $l[$j]-$l[$ta[$i]] < 2+$cf['menu']['levelcatch']) {
            if ($l[$j] > $l[$ta[$i]]){
                $t .= 's';
                break;
            }
        }*/
        $t .= '">';
        if ($tf && isset($pd_router)) {
            $pageData = $pd_router->find_page($ta[$i]);
            $target = !($adm && $edit) && isset($pageData['use_header_location']) && $pageData['use_header_location'] === '2'
                ? '" target="_blank' : '';
        } else {
            $target = '';
        }
        $prueflink=a($ta[$i], (!$classifyLi && $isActive ? '" class="active' : '') . $target);
        $t .= $prueflink;
        $t .= $h[$ta[$i]];
        //if ($tf)
        $t .= '</a>';
        if ($st == 'menulevel' || $st == 'sitemaplevel') {
            if ((isset($ta[$i+1])?$l[$ta[$i+1]]:$b) > $l[$ta[$i]])$lf[$l[$ta[$i]]] = true;
            else
                {
                $t .= '</li>'."\n";
                $lf[$l[$ta[$i]]] = false;
            }
            for($k = $l[$ta[$i]]; $k > (isset($ta[$i+1])?$l[$ta[$i+1]]:$b); $k--) {
                $t .= '</ul>'."\n";
                if (isset($lf[$k-1]))if($lf[$k-1]) {
                    $t .= '</li>'."\n";
                    $lf[$k-1] = false;
                }
            };
        }
        else $t .= '</li>'."\n";
    }
    if ($st == 'submenu' || $st == 'search')$t .= '</ul>'."\n";
    return $t;
}

/**
 * The classic tb_li function.
 */
function tb_li($ta, $st, $prefix = 'art')
{
    return tb_li_hv($ta, $st, "$prefix-vmenu", true);
}

/**
 * Creates horizontal menus.
 */
function tb_li_h($ta, $st, $prefix = 'art')
{
    return tb_li_hv($ta, $st, "$prefix-hmenu", false);
}

/**
 * Creates vertical menues.
 */
function tb_li_v($ta, $st, $prefix = 'art')
{
    return tb_li_hv($ta, $st, "$prefix-vmenu", false);
}

?>