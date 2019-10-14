SELECT
p.codigo AS Codigo,
p.nombre AS Producto,
c.nombre AS Categoria,
p.cantidad_minima AS Stock,
SUM(COALESCE(dc.cantidad,0) - COALESCE(df.cantidad,0)) AS Cantidad,
u.nombre AS Unidad,
p.precio AS Precio,
i.iva AS Iva
	FROM producto p
	LEFT JOIN detalle_compra dc ON p.codigo = dc.codigo_producto
	LEFT JOIN detalle_factura df ON p.codigo = df.codigo_producto
	INNER JOIN categoria c ON p.nombre_categoria = c.id_categoria
	INNER JOIN unidad u ON p.nombre_unidad = u.id_unidad
	INNER JOIN iva i ON p.nombre_iva = i.id_iva
GROUP BY p.codigo;

SELECT 
p.codigo AS Codigo, 
p.nombre AS Producto, 
p.descripcion AS Descripcion, 
c.nombre AS Categoria, 
p.cantidad_minima AS Stock, 
u.nombre AS Unidad, 
p.precio AS Precio, 
i.iva AS Iva
	FROM producto p 
	INNER JOIN categoria c ON p.nombre_categoria = c.id_categoria 
	INNER JOIN unidad u ON p.nombre_unidad = u.id_unidad 
	INNER JOIN iva i ON p.nombre_iva = i.id_iva;

	/*
	#367fa9

	*/

	/*var date = new Date();
  var fecha = date.getDate()+"/"+(date.getMonth()+1)+"/"+date.getFullYear();
  var hora = ("0" + date.getHours()).slice(-2)+":"+("0" + date.getMinutes()).slice(-2)+":"+("0" + date.getSeconds()).slice(-2);*/


  SELECT 
( SELECT SUM(dv.cantidad_vendida) AS cantidad_vendida, 
MONTHNAME(v.fecha_registro) AS mesventa
FROM producto p INNER JOIN detalle_venta dv 
ON p.codigo_producto=dv.codigo_producto INNER JOIN venta V ON V.codigo_venta=DV.codigo_venta) AS tabla1,

( SELECT SUM(dc.precio_compra) AS cantidad_comprada,
MONTHNAME(c.fecha_registro) AS mescompra
FROM compra c INNER JOIN detalle_compra dc 
ON c.codigo_compra=dc.codigo_compra) AS tabla2,
FROM DUAL


SELECT 
SUM(dv.cantidad_vendida) AS cantidad_vendida, 
MONTHNAME(v.fecha_registro) AS mesventa,
SUM(dc.precio_compra) AS cantidad_comprada,
MONTHNAME(c.fecha_registro) AS mescompra
FROM producto p INNER JOIN detalle_venta dv 
ON p.codigo_producto=dv.codigo_producto INNER JOIN venta V ON V.codigo_venta=DV.codigo_venta INNER JOIN detalle_compra dc ON p.codigo_producto=dc.codigo_producto INNER JOIN compra c ON c.codigo_compra=dc.codigo_compra GROUP BY mesventa

SELECT 
p.nombre, SUM(dv.cantidad_vendida) AS cantidad_vendida, MONTHNAME(v.fecha_registro) as mes FROM  producto p INNER JOIN detalle_venta dv 
ON p.codigo_producto=dv.codigo_producto INNER JOIN venta V ON V.codigo_venta=DV.codigo_venta


UPDATE producto SET cantidad_actual = (SELECT SUM(COALESCE((SELECT SUM(COALESCE(cantidad_comprada,0)) FROM detalle_compra WHERE codigo_producto = '".$codigo_producto."'),0) - COALESCE((SELECT SUM(COALESCE(cantidad_vendida,0)) FROM detalle_venta WHERE codigo_producto = '".$codigo_producto."'),0))) WHERE codigo_producto = '".$codigo_producto."';