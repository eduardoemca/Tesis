<?php

function paginate($reload, $page, $tpages, $adjacents, $id_proveedor)
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
		$out.= "<li>".'<span><a href="javascript:void(0);" onclick="load_proveedor_producto(1,'."'$id_proveedor'".')">'."$prevlabel".'</a></span>'."</li>";
	}
	else
	{
		$out.= "<li>".'<span><a href="javascript:void(0);" onclick="load_proveedor_producto('."$page-1".','."'$id_proveedor'".')">'."$prevlabel".'</a></span>'."</li>";

	}
	
	// first label
	if($page>($adjacents+1))
	{
		$out.= "<li>".'<a href="javascript:void(0);"" onclick="load_proveedor_producto(1,'."'$id_proveedor'".')">1</a>'."</li>";
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
		}else if($i==1)
		{
			$out.= "<li>".'<a href="javascript:void(0);" onclick="load_proveedor_producto(1,'."'$id_proveedor'".')">'."$i".'</a>'."</li>";
		}
		else
		{
			$out.= "<li>".'<a href="javascript:void(0);" onclick="load_proveedor_producto('."$i".','."'$id_proveedor'".')">'."$i".'</a>'."</li>";
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
		$out.= "<li>".'<a href="javascript:void(0);" onclick="load_proveedor_producto($tpages,'."'$id_proveedor'".')">'."$tpages".'</a>'."</li>";
	}

	// next

	if($page<$tpages)
	{
		$out.= "<li>".'<span><a href="javascript:void(0);" onclick="load_proveedor_producto('.($page+1).','."'$id_proveedor'".')">'."$nextlabel".'</a></span>'."</li>";
	}
	else
	{
		$out.= "<li class='disabled'>".'<span><a>'."$nextlabel".'</a></span>'."</li>";
	}
	
	$out.= "</ul>";
	return $out;
}
?>