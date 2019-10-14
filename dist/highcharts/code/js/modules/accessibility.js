/*
 Highcharts JS v6.0.6 (2018-02-05)
 Accessibility module

 (c) 2010-2017 Highsoft AS
 Author: Oystein Moseng

 License: www./license
*/
(function(m){"object"===typeof module&&module.exports?module.exports=m:m(Highcharts)})(function(m){(function(e){function m(c){return c.replace(/&/g,"\x26amp;").replace(/</g,"\x26lt;").replace(/>/g,"\x26gt;").replace(/"/g,"\x26quot;").replace(/'/g,"\x26#x27;").replace(/\//g,"\x26#x2F;")}function t(c){return"string"===typeof c?c.replace(/<\/?[^>]+(>|$)/g,""):c}function u(c){for(var b=c.childNodes.length;b--;)c.appendChild(c.childNodes[b])}var l=e.win.document,k=e.each,v=e.erase,g=e.addEvent,n=e.merge,
w={position:"absolute",left:"-9999px",top:"auto",width:"1px",height:"1px",overflow:"hidden"},r={"default":["series","data point","data points"],line:["line","data point","data points"],spline:["line","data point","data points"],area:["line","data point","data points"],areaspline:["line","data point","data points"],pie:["pie","slice","slices"],column:["column series","column","columns"],bar:["bar series","bar","bars"],scatter:["scatter series","data point","data points"],boxplot:["boxplot series",
"box","boxes"],arearange:["arearange series","data point","data points"],areasplinerange:["areasplinerange series","data point","data points"],bubble:["bubble series","bubble","bubbles"],columnrange:["columnrange series","column","columns"],errorbar:["errorbar series","errorbar","errorbars"],funnel:["funnel","data point","data points"],pyramid:["pyramid","data point","data points"],waterfall:["waterfall series","column","columns"],map:["map","area","areas"],mapline:["line","data point","data points"],
mappoint:["point series","data point","data points"],mapbubble:["bubble series","bubble","bubbles"]},y={boxplot:" Box plot charts are typically used to display groups of statistical data. Each data point in the chart can have up to 5 values: minimum, lower quartile, median, upper quartile and maximum. ",arearange:" Arearange charts are line charts displaying a range between a lower and higher value for each point. ",areasplinerange:" These charts are line charts displaying a range between a lower and higher value for each point. ",
bubble:" Bubble charts are scatter charts where each data point also has a size value. ",columnrange:" Columnrange charts are column charts displaying a range between a lower and higher value for each point. ",errorbar:" Errorbar series are used to display the variability of the data. ",funnel:" Funnel charts are used to display reduction of data in stages. ",pyramid:" Pyramid charts consist of a single pyramid with item heights corresponding to each point value. ",waterfall:" A waterfall chart is a column chart where each column contributes towards a total end value. "};
e.Series.prototype.commonKeys="name id category x value y".split(" ");e.Series.prototype.specialKeys="z open high q3 median q1 low close".split(" ");e.seriesTypes.pie&&(e.seriesTypes.pie.prototype.specialKeys=[]);e.setOptions({accessibility:{enabled:!0,pointDescriptionThreshold:!1}});e.wrap(e.Series.prototype,"render",function(c){c.apply(this,Array.prototype.slice.call(arguments,1));this.chart.options.accessibility.enabled&&this.setA11yDescription()});e.Series.prototype.setA11yDescription=function(){var c=
this.chart.options.accessibility,b=this.points&&this.points.length&&this.points[0].graphic&&this.points[0].graphic.element,a=b&&b.parentNode||this.graph&&this.graph.element||this.group&&this.group.element;a&&(a.lastChild===b&&u(a),this.points&&(this.points.length<c.pointDescriptionThreshold||!1===c.pointDescriptionThreshold)&&k(this.points,function(a){a.graphic&&(a.graphic.element.setAttribute("role","img"),a.graphic.element.setAttribute("tabindex","-1"),a.graphic.element.setAttribute("aria-label",
t(a.series.options.pointDescriptionFormatter&&a.series.options.pointDescriptionFormatter(a)||c.pointDescriptionFormatter&&c.pointDescriptionFormatter(a)||a.buildPointInfoString())))}),1<this.chart.series.length||c.describeSingleSeries)&&(a.setAttribute("role",this.options.exposeElementToA11y?"img":"region"),a.setAttribute("tabindex","-1"),a.setAttribute("aria-label",t(c.seriesDescriptionFormatter&&c.seriesDescriptionFormatter(this)||this.buildSeriesInfoString())))};e.Series.prototype.buildSeriesInfoString=
function(){var c=r[this.type]||r["default"],b=this.description||this.options.description;return(this.name?this.name+", ":"")+(1===this.chart.types.length?c[0]:"series")+" "+(this.index+1)+" of "+this.chart.series.length+(1===this.chart.types.length?" with ":". "+c[0]+" with ")+(this.points.length+" "+(1===this.points.length?c[1]:c[2]))+(b?". "+b:"")+(1<this.chart.yAxis.length&&this.yAxis?". Y axis, "+this.yAxis.getDescription():"")+(1<this.chart.xAxis.length&&this.xAxis?". X axis, "+this.xAxis.getDescription():
"")};e.Point.prototype.buildPointInfoString=function(){var c=this,b=c.series,a=b.chart.options.accessibility,d="",f=b.xAxis&&b.xAxis.isDatetimeAxis,a=f&&b.chart.time.dateFormat.call(a.pointDateFormatter&&a.pointDateFormatter(c)||a.pointDateFormat||e.Tooltip.prototype.getXDateFormat.call({getDateFormat:e.Tooltip.prototype.getDateFormat,chart:b.chart},c,b.chart.options.tooltip,b.xAxis),c.x);e.find(b.specialKeys,function(a){return void 0!==c[a]})?(f&&(d=a),k(b.commonKeys.concat(b.specialKeys),function(a){void 0===
c[a]||f&&"x"===a||(d+=(d?". ":"")+a+", "+c[a])})):d=(this.name||a||this.category||this.id||"x, "+this.x)+", "+(void 0!==this.value?this.value:this.y);return this.index+1+". "+d+"."+(this.description?" "+this.description:"")};e.Axis.prototype.getDescription=function(){return this.userOptions&&this.userOptions.description||this.axisTitle&&this.axisTitle.textStr||this.options.id||this.categories&&"categories"||"values"};e.wrap(e.Series.prototype,"init",function(c){c.apply(this,Array.prototype.slice.call(arguments,
1));var b=this.chart;b.options.accessibility.enabled&&(b.types=b.types||[],0>b.types.indexOf(this.type)&&b.types.push(this.type),g(this,"remove",function(){var a=this,d=!1;k(b.series,function(f){f!==a&&0>b.types.indexOf(a.type)&&(d=!0)});d||v(b.types,a.type)}))});e.Chart.prototype.getTypeDescription=function(){var c=this.types&&this.types[0],b=this.series[0]&&this.series[0].mapTitle;if(c){if("map"===c)return b?"Map of "+b:"Map of unspecified region.";if(1<this.types.length)return"Combination chart.";
if(-1<["spline","area","areaspline"].indexOf(c))return"Line chart."}else return"Empty chart.";return c+" chart."+(y[c]||"")};e.Chart.prototype.getAxesDescription=function(){var c=this.xAxis.length,b=this.yAxis.length,a={},d;if(c)if(a.xAxis="The chart has "+c+(1<c?" X axes":" X axis")+" displaying ",2>c)a.xAxis+=this.xAxis[0].getDescription()+".";else{for(d=0;d<c-1;++d)a.xAxis+=(d?", ":"")+this.xAxis[d].getDescription();a.xAxis+=" and "+this.xAxis[d].getDescription()+"."}if(b)if(a.yAxis="The chart has "+
b+(1<b?" Y axes":" Y axis")+" displaying ",2>b)a.yAxis+=this.yAxis[0].getDescription()+".";else{for(d=0;d<b-1;++d)a.yAxis+=(d?", ":"")+this.yAxis[d].getDescription();a.yAxis+=" and "+this.yAxis[d].getDescription()+"."}return a};e.Chart.prototype.addAccessibleContextMenuAttribs=function(){var c=this.exportDivElements;c&&(k(c,function(b){"DIV"!==b.tagName||b.children&&b.children.length||(b.setAttribute("role","menuitem"),b.setAttribute("tabindex",-1))}),c[0].parentNode.setAttribute("role","menu"),c[0].parentNode.setAttribute("aria-label",
"Chart export"))};e.Chart.prototype.addScreenReaderRegion=function(c,b){var a=this,d=a.series,f=a.options,e=f.accessibility,p=a.screenReaderRegion=l.createElement("div"),h=l.createElement("h4"),x=l.createElement("a"),k=l.createElement("h4"),q=a.types||[],q=(1===q.length&&"pie"===q[0]||"map"===q[0])&&{}||a.getAxesDescription(),g=d[0]&&r[d[0].type]||r["default"];p.setAttribute("id",c);p.setAttribute("role","region");p.setAttribute("aria-label","Chart screen reader information.");p.innerHTML=e.screenReaderSectionFormatter&&
e.screenReaderSectionFormatter(a)||"\x3cdiv\x3eUse regions/landmarks to skip ahead to chart"+(1<d.length?" and navigate between data series":"")+".\x3c/div\x3e\x3ch3\x3e"+(f.title.text?m(f.title.text):"Chart")+(f.subtitle&&f.subtitle.text?". "+m(f.subtitle.text):"")+"\x3c/h3\x3e\x3ch4\x3eLong description.\x3c/h4\x3e\x3cdiv\x3e"+(f.chart.description||"No description available.")+"\x3c/div\x3e\x3ch4\x3eStructure.\x3c/h4\x3e\x3cdiv\x3eChart type: "+(f.chart.typeDescription||a.getTypeDescription())+"\x3c/div\x3e"+
(1===d.length?"\x3cdiv\x3e"+g[0]+" with "+d[0].points.length+" "+(1===d[0].points.length?g[1]:g[2])+".\x3c/div\x3e":"")+(q.xAxis?"\x3cdiv\x3e"+q.xAxis+"\x3c/div\x3e":"")+(q.yAxis?"\x3cdiv\x3e"+q.yAxis+"\x3c/div\x3e":"");a.getCSV&&(x.innerHTML="View as data table.",x.href="#"+b,x.setAttribute("tabindex","-1"),x.onclick=e.onTableAnchorClick||function(){a.viewData();l.getElementById(b).focus()},h.appendChild(x),p.appendChild(h));k.innerHTML="Chart graphic.";a.renderTo.insertBefore(k,a.renderTo.firstChild);
a.renderTo.insertBefore(p,a.renderTo.firstChild);n(!0,k.style,w);n(!0,p.style,w)};e.Chart.prototype.callbacks.push(function(c){var b=c.options;if(b.accessibility.enabled){var a=l.createElementNS("http://www.w3.org/2000/svg","title"),d=l.createElementNS("http://www.w3.org/2000/svg","g"),f=c.container.getElementsByTagName("desc")[0],z=c.container.getElementsByTagName("text"),p="highcharts-title-"+c.index,h="highcharts-data-table-"+c.index,g="highcharts-information-region-"+c.index,b=b.title.text||"Chart";
a.textContent=m(b);a.id=p;f.parentNode.insertBefore(a,f);c.renderTo.setAttribute("role","region");c.renderTo.setAttribute("aria-label",t("Interactive chart. "+b+". Use up and down arrows to navigate with most screen readers."));if(c.exportSVGElements&&c.exportSVGElements[0]&&c.exportSVGElements[0].element){var n=c.exportSVGElements[0].element.onclick,a=c.exportSVGElements[0].element.parentNode;c.exportSVGElements[0].element.onclick=function(){n.apply(this,Array.prototype.slice.call(arguments));c.addAccessibleContextMenuAttribs();
c.highlightExportItem(0)};c.exportSVGElements[0].element.setAttribute("role","button");c.exportSVGElements[0].element.setAttribute("aria-label","View export menu");d.appendChild(c.exportSVGElements[0].element);d.setAttribute("role","region");d.setAttribute("aria-label","Chart export menu");a.appendChild(d)}c.rangeSelector&&k(["minInput","maxInput"],function(a,b){c.rangeSelector[a]&&(c.rangeSelector[a].setAttribute("tabindex","-1"),c.rangeSelector[a].setAttribute("role","textbox"),c.rangeSelector[a].setAttribute("aria-label",
"Select "+(b?"end":"start")+" date."))});k(z,function(a){a.setAttribute("aria-hidden","true")});c.addScreenReaderRegion(g,h);e.wrap(c,"getTable",function(a){return a.apply(this,Array.prototype.slice.call(arguments,1)).replace("\x3ctable\x3e",'\x3ctable id\x3d"'+h+'" summary\x3d"Table representation of chart"\x3e')})}})})(m);(function(e){function m(b){return"string"===typeof b?b.replace(/<\/?[^>]+(>|$)/g,""):b}function t(b,a){this.chart=b;this.id=a.id;this.keyCodeMap=a.keyCodeMap;this.validate=a.validate;
this.init=a.init;this.terminate=a.terminate}function u(b){var a;b&&b.onclick&&v.createEvent&&(a=v.createEvent("Events"),a.initEvent("click",!0,!1),b.onclick(a))}function l(b){var a=b.series.chart.options.accessibility;return b.isNull&&a.keyboardNavigation.skipNullPoints||b.series.options.skipKeyboardNavigation||!b.series.visible||!1===b.visible||a.pointDescriptionThreshold&&a.pointDescriptionThreshold<=b.series.points.length}var k=e.win,v=k.document,g=e.each,n=e.addEvent,w=e.fireEvent,r=e.merge,y=
e.pick,c;e.extend(e.SVGElement.prototype,{addFocusBorder:function(b,a){this.focusBorder&&this.removeFocusBorder();var d=this.getBBox();b=y(b,3);this.focusBorder=this.renderer.rect(d.x-b,d.y-b,d.width+2*b,d.height+2*b,a&&a.borderRadius).addClass("highcharts-focus-border").attr({zIndex:99}).add(this.parentGroup)},removeFocusBorder:function(){this.focusBorder&&(this.focusBorder.destroy(),delete this.focusBorder)}});e.Series.prototype.keyboardMoveVertical=!0;g(["column","pie"],function(b){e.seriesTypes[b]&&
(e.seriesTypes[b].prototype.keyboardMoveVertical=!1)});e.setOptions({accessibility:{keyboardNavigation:{enabled:!0,focusBorder:{enabled:!0,hideBrowserFocusOutline:!0,style:{color:"#335cad",lineWidth:2,borderRadius:3},margin:2},skipNullPoints:!0}}});t.prototype={run:function(b){var a=this,d=b.which||b.keyCode,f=!1,c=!1;g(this.keyCodeMap,function(e){-1<e[0].indexOf(d)&&(f=!0,c=!1===e[1].call(a,d,b)?!1:!0)});f||9!==d||(c=this.move(b.shiftKey?-1:1));return c},move:function(b){var a=this.chart;this.terminate&&
this.terminate(b);a.keyboardNavigationModuleIndex+=b;var d=a.keyboardNavigationModules[a.keyboardNavigationModuleIndex];a.focusElement&&a.focusElement.removeFocusBorder();if(d){if(d.validate&&!d.validate())return this.move(b);if(d.init)return d.init(b),!0}a.keyboardNavigationModuleIndex=0;0<b?(this.chart.exiting=!0,this.chart.tabExitAnchor.focus()):this.chart.renderTo.focus();return!1}};e.Axis.prototype.panStep=function(b,a){var d=a||3;a=this.getExtremes();var f=(a.max-a.min)/d*b,d=a.max+f,f=a.min+
f,c=d-f;0>b&&f<a.dataMin?(f=a.dataMin,d=f+c):0<b&&d>a.dataMax&&(d=a.dataMax,f=d-c);this.setExtremes(f,d)};e.Chart.prototype.setFocusToElement=function(b,a){var d=this.options.accessibility.keyboardNavigation.focusBorder;a=a||b;a.element&&a.element.focus&&(a.element.focus(),d.hideBrowserFocusOutline&&a.css({outline:"none"}));d.enabled&&b!==this.focusElement&&(this.focusElement&&this.focusElement.removeFocusBorder(),b.addFocusBorder(d.margin,{stroke:d.style.color,strokeWidth:d.style.lineWidth,borderRadius:d.style.borderRadius}),
this.focusElement=b)};e.Point.prototype.highlight=function(){var b=this.series.chart;if(this.isNull)b.tooltip&&b.tooltip.hide(0);else this.onMouseOver();this.graphic&&b.setFocusToElement(this.graphic);b.highlightedPoint=this;return this};e.Chart.prototype.highlightAdjacentPoint=function(b){var a=this.series,d=this.highlightedPoint,f=d&&d.index||0,c=d&&d.series.points,e=this.series&&this.series[this.series.length-1],e=e&&e.points&&e.points[e.points.length-1];if(!a[0]||!a[0].points)return!1;if(d){if(c[f]!==
d)for(e=0;e<c.length;++e)if(c[e]===d){f=e;break}a=a[d.series.index+(b?1:-1)];f=c[f+(b?1:-1)]||a&&a.points[b?0:a.points.length-1];if(!f)return!1}else f=b?a[0].points[0]:e;return l(f)?(this.highlightedPoint=f,this.highlightAdjacentPoint(b)):f.highlight()};e.Series.prototype.highlightFirstValidPoint=function(){var b=this.chart.highlightedPoint,a=(b&&b.series)===this?b.index:0;if(b=this.points){for(var d=a,f=b.length;d<f;++d)if(!l(b[d]))return b[d].highlight();for(;0<=a;--a)if(!l(b[a]))return b[a].highlight()}return!1};
e.Chart.prototype.highlightAdjacentSeries=function(b){var a,d,f=this.highlightedPoint,c=(a=this.series&&this.series[this.series.length-1])&&a.points&&a.points[a.points.length-1];if(!this.highlightedPoint)return a=b?this.series&&this.series[0]:a,(d=b?a&&a.points&&a.points[0]:c)?d.highlight():!1;a=this.series[f.series.index+(b?-1:1)];if(!a)return!1;var c=Infinity,e,h=a.points.length;if(void 0===f.plotX||void 0===f.plotY)d=void 0;else{for(;h--;)e=a.points[h],void 0!==e.plotX&&void 0!==e.plotY&&(e=(f.plotX-
e.plotX)*(f.plotX-e.plotX)*4+(f.plotY-e.plotY)*(f.plotY-e.plotY)*1,e<c&&(c=e,d=h));d=void 0!==d&&a.points[d]}if(!d)return!1;if(!a.visible)return d.highlight(),b=this.highlightAdjacentSeries(b),b?b:(f.highlight(),!1);d.highlight();return d.series.highlightFirstValidPoint()};e.Chart.prototype.highlightAdjacentPointVertical=function(b){var a=this.highlightedPoint,d=Infinity,c;if(void 0===a.plotX||void 0===a.plotY)return!1;g(this.series,function(f){g(f.points,function(e){if(void 0!==e.plotY&&void 0!==
e.plotX&&e!==a){var h=e.plotY-a.plotY,g=Math.abs(e.plotX-a.plotX),g=Math.abs(h)*Math.abs(h)+g*g*4;f.yAxis.reversed&&(h*=-1);!(0>h&&b||0<h&&!b||5>g||l(e))&&g<d&&(d=g,c=e)}})});return c?c.highlight():!1};e.Chart.prototype.showExportMenu=function(){this.exportSVGElements&&this.exportSVGElements[0]&&(this.exportSVGElements[0].element.onclick(),this.highlightExportItem(0))};e.Chart.prototype.hideExportMenu=function(){var b=this.exportDivElements;if(b){g(b,function(a){w(a,"mouseleave")});if(b[this.highlightedExportItem]&&
b[this.highlightedExportItem].onmouseout)b[this.highlightedExportItem].onmouseout();this.highlightedExportItem=0;c&&this.renderTo.focus()}};e.Chart.prototype.highlightExportItem=function(b){var a=this.exportDivElements&&this.exportDivElements[b],d=this.exportDivElements&&this.exportDivElements[this.highlightedExportItem];if(a&&"DIV"===a.tagName&&(!a.children||!a.children.length)){a.focus&&c&&a.focus();if(d&&d.onmouseout)d.onmouseout();if(a.onmouseover)a.onmouseover();this.highlightedExportItem=b;
return!0}};e.Chart.prototype.highlightLastExportItem=function(){var b;if(this.exportDivElements)for(b=this.exportDivElements.length;b--&&!this.highlightExportItem(b););};e.Chart.prototype.highlightRangeSelectorButton=function(b){var a=this.rangeSelector.buttons;a[this.highlightedRangeSelectorItemIx]&&a[this.highlightedRangeSelectorItemIx].setState(this.oldRangeSelectorItemState||0);this.highlightedRangeSelectorItemIx=b;return a[b]?(this.setFocusToElement(a[b].box,a[b]),this.oldRangeSelectorItemState=
a[b].state,a[b].setState(2),!0):!1};e.Chart.prototype.highlightLegendItem=function(b){var a=this.legend.allItems,d=this.highlightedLegendItemIx;return a[b]?(a[d]&&w(a[d].legendGroup.element,"mouseout"),void 0!==a[b].pageIx&&a[b].pageIx+1!==this.legend.currentPage&&this.legend.scroll(1+a[b].pageIx-this.legend.currentPage),this.highlightedLegendItemIx=b,this.setFocusToElement(a[b].legendItem,a[b].legendGroup),w(a[b].legendGroup.element,"mouseover"),!0):!1};e.Chart.prototype.addKeyboardNavigationModules=
function(){function b(b,c,e){return new t(a,r({keyCodeMap:c},{id:b},e))}var a=this;a.keyboardNavigationModules=[b("entry",[]),b("points",[[[37,39],function(b){b=39===b;return a.highlightAdjacentPoint(b)?!0:this.init(b?1:-1)}],[[38,40],function(b){b=38!==b;var d=a.options.accessibility.keyboardNavigation;if(d.mode&&"serialize"===d.mode)return a.highlightAdjacentPoint(b)?!0:this.init(b?1:-1);a[a.highlightedPoint&&a.highlightedPoint.series.keyboardMoveVertical?"highlightAdjacentPointVertical":"highlightAdjacentSeries"](b);
return!0}],[[13,32],function(){a.highlightedPoint&&a.highlightedPoint.firePointEvent("click")}]],{init:function(b){var d=a.series.length,c=0<b?0:d;if(0<b)for(delete a.highlightedPoint;c<d;){if(b=a.series[c].highlightFirstValidPoint())return b;++c}else for(;c--;)if(a.highlightedPoint=a.series[c].points[a.series[c].points.length-1],b=a.series[c].highlightFirstValidPoint())return b},terminate:function(){a.tooltip&&a.tooltip.hide(0);delete a.highlightedPoint}}),b("exporting",[[[37,38],function(){for(var b=
a.highlightedExportItem||0,c=!0;b--;)if(a.highlightExportItem(b)){c=!1;break}if(c)return a.highlightLastExportItem(),!0}],[[39,40],function(){for(var b=!0,c=(a.highlightedExportItem||0)+1;c<a.exportDivElements.length;++c)if(a.highlightExportItem(c)){b=!1;break}if(b)return a.highlightExportItem(0),!0}],[[13,32],function(){u(a.exportDivElements[a.highlightedExportItem])}]],{validate:function(){return a.exportChart&&!(a.options.exporting&&!1===a.options.exporting.enabled)},init:function(b){a.highlightedPoint=
null;a.showExportMenu();0>b&&a.highlightLastExportItem()},terminate:function(){a.hideExportMenu()}}),b("mapZoom",[[[38,40,37,39],function(b){a[38===b||40===b?"yAxis":"xAxis"][0].panStep(39>b?-1:1)}],[[9],function(b,c){a.mapNavButtons[a.focusedMapNavButtonIx].setState(0);if(c.shiftKey&&!a.focusedMapNavButtonIx||!c.shiftKey&&a.focusedMapNavButtonIx)return a.mapZoom(),this.move(c.shiftKey?-1:1);a.focusedMapNavButtonIx+=c.shiftKey?-1:1;b=a.mapNavButtons[a.focusedMapNavButtonIx];a.setFocusToElement(b.box,
b);b.setState(2)}],[[13,32],function(){u(a.mapNavButtons[a.focusedMapNavButtonIx].element)}]],{validate:function(){return a.mapZoom&&a.mapNavButtons&&2===a.mapNavButtons.length},init:function(b){var d=a.mapNavButtons[0],c=a.mapNavButtons[1],d=0<b?d:c;g(a.mapNavButtons,function(a,b){a.element.setAttribute("tabindex",-1);a.element.setAttribute("role","button");a.element.setAttribute("aria-label","Zoom "+(b?"out ":"")+"chart")});a.setFocusToElement(d.box,d);d.setState(2);a.focusedMapNavButtonIx=0<b?
0:1}}),b("rangeSelector",[[[37,39,38,40],function(b){b=37===b||38===b?-1:1;if(!a.highlightRangeSelectorButton(a.highlightedRangeSelectorItemIx+b))return this.move(b)}],[[13,32],function(){3!==a.oldRangeSelectorItemState&&u(a.rangeSelector.buttons[a.highlightedRangeSelectorItemIx].element)}]],{validate:function(){return a.rangeSelector&&a.rangeSelector.buttons&&a.rangeSelector.buttons.length},init:function(b){g(a.rangeSelector.buttons,function(a){a.element.setAttribute("tabindex","-1");a.element.setAttribute("role",
"button");a.element.setAttribute("aria-label","Select range "+(a.text&&a.text.textStr))});a.highlightRangeSelectorButton(0<b?0:a.rangeSelector.buttons.length-1)}}),b("rangeSelectorInput",[[[9,38,40],function(b,c){b=9===b&&c.shiftKey||38===b?-1:1;c=a.highlightedInputRangeIx+=b;if(1<c||0>c)return this.move(b);a.rangeSelector[c?"maxInput":"minInput"].focus()}]],{validate:function(){return a.rangeSelector&&a.rangeSelector.inputGroup&&"hidden"!==a.rangeSelector.inputGroup.element.getAttribute("visibility")&&
!1!==a.options.rangeSelector.inputEnabled&&a.rangeSelector.minInput&&a.rangeSelector.maxInput},init:function(b){a.highlightedInputRangeIx=0<b?0:1;a.rangeSelector[a.highlightedInputRangeIx?"maxInput":"minInput"].focus()}}),b("legend",[[[37,39,38,40],function(b){b=37===b||38===b?-1:1;a.highlightLegendItem(a.highlightedLegendItemIx+b)||this.init(b)}],[[13,32],function(){u(a.legend.allItems[a.highlightedLegendItemIx].legendItem.element.parentNode)}]],{validate:function(){return a.legend&&a.legend.allItems&&
a.legend.display&&!(a.colorAxis&&a.colorAxis.length)&&!1!==(a.options.legend&&a.options.legend.keyboardNavigation&&a.options.legend.keyboardNavigation.enabled)},init:function(b){g(a.legend.allItems,function(a){a.legendGroup.element.setAttribute("tabindex","-1");a.legendGroup.element.setAttribute("role","button");a.legendGroup.element.setAttribute("aria-label",m("Toggle visibility of series "+a.name))});a.highlightLegendItem(0<b?0:a.legend.allItems.length-1)}})]};e.Chart.prototype.addExitAnchor=function(){var b=
this;b.tabExitAnchor=v.createElement("div");b.tabExitAnchor.setAttribute("tabindex","0");r(!0,b.tabExitAnchor.style,{position:"absolute",left:"-9999px",top:"auto",width:"1px",height:"1px",overflow:"hidden"});b.renderTo.appendChild(b.tabExitAnchor);return n(b.tabExitAnchor,"focus",function(a){a=a||k.event;b.exiting?b.exiting=!1:(b.renderTo.focus(),a.preventDefault(),b.keyboardNavigationModuleIndex=b.keyboardNavigationModules.length-1,a=b.keyboardNavigationModules[b.keyboardNavigationModuleIndex],a.validate&&
!a.validate()?a.move(-1):a.init(-1))})};e.Chart.prototype.resetKeyboardNavigation=function(){var b=this.keyboardNavigationModules&&this.keyboardNavigationModules[this.keyboardNavigationModuleIndex||0];b&&b.terminate&&b.terminate();this.focusElement&&this.focusElement.removeFocusBorder();this.keyboardNavigationModuleIndex=0;this.keyboardReset=!0};e.wrap(e.Series.prototype,"destroy",function(b){var a=this.chart;a.highlightedPoint&&a.highlightedPoint.series===this&&(delete a.highlightedPoint,a.focusElement&&
a.focusElement.removeFocusBorder());b.apply(this,Array.prototype.slice.call(arguments,1))});e.Chart.prototype.callbacks.push(function(b){var a=b.options.accessibility;a.enabled&&a.keyboardNavigation.enabled&&(c=!!b.renderTo.getElementsByTagName("g")[0].focus,b.addKeyboardNavigationModules(),b.keyboardNavigationModuleIndex=0,b.container.hasAttribute&&!b.container.hasAttribute("tabIndex")&&b.container.setAttribute("tabindex","0"),b.tabExitAnchor||(b.unbindExitAnchorFocus=b.addExitAnchor()),b.unbindKeydownHandler=
n(b.renderTo,"keydown",function(a){a=a||k.event;var c=b.keyboardNavigationModules[b.keyboardNavigationModuleIndex];b.keyboardReset=!1;c&&c.run(a)&&a.preventDefault()}),b.unbindBlurHandler=n(v,"mouseup",function(){b.keyboardReset||b.pointer&&b.pointer.chartPosition||b.resetKeyboardNavigation()}),n(b,"destroy",function(){b.resetKeyboardNavigation();b.unbindExitAnchorFocus&&b.tabExitAnchor&&b.unbindExitAnchorFocus();b.unbindKeydownHandler&&b.renderTo&&b.unbindKeydownHandler();b.unbindBlurHandler&&b.unbindBlurHandler()}))})})(m)});