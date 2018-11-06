<?php

//寫入內容
function writeContent($sheet,$col,$col_row,$content,$align,$v_align,$format,$auto_wrap){

 	// const HORIZONTAL_GENERAL = 'general';
  //   const HORIZONTAL_LEFT = 'left';
  //   const HORIZONTAL_RIGHT = 'right';
  //   const HORIZONTAL_CENTER = 'center';
  //   const HORIZONTAL_CENTER_CONTINUOUS = 'centerContinuous';
  //   const HORIZONTAL_JUSTIFY = 'justify';
  //   const HORIZONTAL_FILL = 'fill';
  //   const HORIZONTAL_DISTRIBUTED = 'distributed'; // Excel2007 only

  //   // Vertical alignment styles
  //   const VERTICAL_BOTTOM = 'bottom';
  //   const VERTICAL_TOP = 'top';
  //   const VERTICAL_CENTER = 'center';
  //   const VERTICAL_JUSTIFY = 'justify';
  //   const VERTICAL_DISTRIBUTED = 'distributed'; // Excel2007 only

	$node = $col.$col_row;

	$sheet->setCellValue($node,$content);

	if($align=='')$align = 'left';
	if($v_align=='')$v_align = 'center';

	if($format=='string'){
		$format = 'General';
	}else if($format=='num'){
		$format = '#,##0';
	}else if($format=='num0'){
		$format = '#,##0;0;-#,##0';
	}else if($format=='date'){
		$format = 'yyyy/mm/dd';
	}

	$sheet->getStyle($node)->applyFromArray(
		[
			'alignment'=>[
				'horizontal'=>$align,
				'vertical'=>$v_align,
				'wrapText'=>$auto_wrap
			],	
			'numberFormat'=>[
				'formatCode'=>$format
			]
		]			
	);

	// $sheet->getStyle($node)->setHorizontal($align);
	// $sheet->getStyle($node)->setVertical($v_align);


	// $sheet->getStyle($node)->setFormatCode($format);
	// $sheet->getStyle($node)->getAlignment()->setWrapText($auto_wrap);
}


//設定欄寬
function setColWidth($sheet,$col,$width){
	if($width=='auto'){
		$sheet->getColumnDimension($col)->setAutoSize(true);	
	}else{
		$sheet->getColumnDimension($col)->setWidth($width);
	}
}

//列高
function setRowHeight($sheet,$row,$height){
	// if($width=='auto'){
	// 	$sheet->getColumnDimension($col)->setAutoSize(true);	
	// }else{
		$sheet->getRowDimension($row)->setRowHeight($height);
	// }
}


//畫框線
function setColBorder($sheet,$node,$border){
	/* border style */ 
	/*
 	const BORDER_NONE = 'none';
    const BORDER_DASHDOT = 'dashDot';
    const BORDER_DASHDOTDOT = 'dashDotDot';
    const BORDER_DASHED = 'dashed';
    const BORDER_DOTTED = 'dotted';
    const BORDER_DOUBLE = 'double';
    const BORDER_HAIR = 'hair';
    const BORDER_MEDIUM = 'medium';
    const BORDER_MEDIUMDASHDOT = 'mediumDashDot';
    const BORDER_MEDIUMDASHDOTDOT = 'mediumDashDotDot';
    const BORDER_MEDIUMDASHED = 'mediumDashed';
    const BORDER_SLANTDASHDOT = 'slantDashDot';
    const BORDER_THICK = 'thick';
    const BORDER_THIN = 'thin';
	*/

	$arr=[];
    foreach($border as $key=>$value){
		if($key=='T'){
			$arr['top'] = ['borderStyle'=>$value];
		}else if($key=='B'){
			$arr['bottom'] = ['borderStyle'=>$value];
		}else if($key=='L'){
			$arr['left'] = ['borderStyle'=>$value];
		}else if($key=='R'){
			$arr['right'] = ['borderStyle'=>$value];
		}else{
			$arr[$key] = ['borderStyle' => $value];
		}
	}
	
	$styleArray = ['borders'=>$arr];

	// var_dump($styleArray);
	$sheet->getStyle($node)->applyFromArray($styleArray);
}

//填色
function setColColor($sheet,$node,$color){

	$sheet->getStyle($node)->applyFromArray(
		['fill'=>
			[
				'fillType'=>'solid',
				'color'=>['argb'=>$color]
			]
		]
	);	
}


function setColMerge($sheet,$col,$col_row,$col_range,$col_row_range){
	$col2 = $col;
	for($a=1;$a<$col_range;$a++){
		$col2++;
	}
	$col_row2 =(!empty($col_row_range)&& is_numeric($col_row_range))?$col_row+($col_row_range-1):$col_row;

	$node = $col.$col_row.':'.$col2.$col_row2;
	$sheet->mergeCells($node);

	return $col2;
}



//綜合
function setFontStyle($sheet,$node,$arr){

	if(!empty($arr['color'])){
		setFontColor($sheet,$node,$arr['color']);
	}else if(!empty($arr['size'])){
		setFontSize($sheet,$node,$arr['size']);
	}else if(!empty($arr['bold'])){
		setFontBold($sheet,$node,$arr['bold']);
	}

}

//設定顏色
function setFontColor($sheet,$node,$color){
	$sheet->getStyle($node)->getFont()->setColor(['argb'=>$color]);
}

//設定大小
function setFontSize($sheet,$node,$size){
	$sheet->getStyle($node)->getFont()->setSize($size);
}

//設定粗體
function setFontBold($sheet,$node,$bold){
	$sheet->getStyle($node)->getFont()->setBold($bold);
}


