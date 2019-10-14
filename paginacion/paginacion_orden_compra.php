<?php

function paginate($reload, $page, $tpages, $adjacents, $orden_compra, $identificacion)
{
	$prevlabel = "&lsaquo; Anterior";
	$nextlabel = "Siguiente &rsaquo;";
	$out = '<ul class="pagination pagination-large">';
	
	// previous label

	if($page==1)
	{
		$out.= "<li class='disabled'>".'<span><a>'."$prevlabel".'</a></span>'."</li>";
	}
	else if($page==2)
	{
		$out.= "<li>".'<span><a href="javascript:void(0);" onclick="load_orden_compra(1,'."'$orden_compra'".','."'$identificacion'".')">'."$prevlabel".'</a></span>'."</li>";
	}
	else
	{
		$out.= "<li>".'<span><a href="javascript:void(0);" onclick="load_orden_compra('."$page-1".','."'$orden_compra'".','."'$identificacion'".')">'."$prevlabel".'</a></span>'."</li>";
	}
	
	// first label
	if($page>($adjacents+1))
	{
		$out.= "<li>".'<a href="javascript:void(0);" onclick="load_orden_compra(1,'."'$orden_compra'".','."'$identificacion'".')">1</a>'."</li>";
	}
	// interval
	if($page>($adjacents+2))
	{
		$out.= "<li><a>...</a></li>";
	}

	// pages

	$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	for($i=$pmin; $i<=$pmax; $i++)
	{
		if($i==$page)
		{
			$out.= "<li class='active'>".'<a>'."$i".'</a>'."</li>";
		}
		else if($i==1)
		{
			$out.= "<li>".'<a href="javascript:void(0);" onclick="load_orden_compra(1,'."'$orden_compra'".','."'$identificacion'".')">'."$i".'</a>'."</li>";
		}
		else
		{
			$out.= "<li>".'<a href="javascript:void(0);" onclick="load_orden_compra('."$i".','."'$orden_compra'".','."'$identificacion'".')">'."$i".'</a>'."</li>";
		}
	}

	// interval

	if($page<($tpages-$adjacents-1))
	{
		$out.= "<li><a>...</a></li>";
	}

	// last

	if($page<($tpages-$adjacents))
	{
		$out.= "<li>".'<a href="javascript:void(0);" onclick="load_orden_compra($tpages,'."'$orden_compra'".','."'$identificacion'".')">'."$tpages".'</a>'."</li>";
	}

	// next

	if($page<$tpages)
	{
		$out.= "<li>".'<span><a href="javascript:void(0);" onclick="load_orden_compra('.($page+1).','."'$orden_compra'".','."'$identificacion'".')">'."$nextlabel".'</a></span>'."</li>";
	}
	else
	{
		$out.= "<li class='disabled'>".'<span><a>'."$nextlabel".'</a></span>'."</li>";
	}
	
	$out.= "</ul>";
	return $out;
}
?>
