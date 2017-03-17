<?
function Month_TH_Short($Month){
    $Month_TH_Short["01"] = "ม.ค.";
    $Month_TH_Short["02"] = "ก.พ.";
    $Month_TH_Short["03"] = "มี.ค.";
    $Month_TH_Short["04"] = "เม.ย.";
    $Month_TH_Short["05"] = "พ.ค.";
    $Month_TH_Short["06"] = "มิ.ย.";
    $Month_TH_Short["07"] = "ก.ค.";
    $Month_TH_Short["08"] = "ส.ค.";
    $Month_TH_Short["09"] = "ก.ย.";
    $Month_TH_Short["10"] = "ต.ค.";
    $Month_TH_Short["11"] = "พ.ย.";
    $Month_TH_Short["12"] = "ธ.ค.";
    return $Month_TH_Short[$Month];
}

function Month_TH_Long($Month){
    $Month_TH_Long["01"] = "มกราคม";
    $Month_TH_Long["02"] = "กุมภาพันธ์";
    $Month_TH_Long["03"] = "มีนาคม";
    $Month_TH_Long["04"] = "เมษายน";
    $Month_TH_Long["05"] = "พฤษภาคม";
    $Month_TH_Long["06"] = "มิถุนายน";
    $Month_TH_Long["07"] = "กรกฎาคม";
    $Month_TH_Long["08"] = "สิงหาคม";
    $Month_TH_Long["09"] = "กันยายน";
    $Month_TH_Long["10"] = "ตุลาคม";
    $Month_TH_Long["11"] = "พฤศจิกายน";
    $Month_TH_Long["12"] = "ธันวาคม";
    return $Month_TH_Long[$Month];
}

function Date_TH_Short($in,$time){
    if($in=="t"){
        $Date = date('Y-m-j H:i:s',$time);
    }
    list($YY,$MM,$DD,$Hour,$Min,$Sec) = split('[-, ,:]',$Date);
    $YY = $YY-1957;
    $OutPut = $DD." ".Month_TH_Short($MM).$YY; 
    return $OutPut;
}

function DateTime_TH_Short($in,$time){
    if($in=="t"){
        $Date = date('Y-m-j H:i:s',$time);
    }
    list($YY,$MM,$DD,$Hour,$Min,$Sec) = split('[-, ,:]',$Date);
    $YY = $YY-1957;
    $OutPut = $DD." ".Month_TH_Short($MM).$YY." ".$Hour.":".$Min; 
    return $OutPut;
}

function Date_TH_Long($in,$time){
    if($in=="t"){
        $Date = date('Y-m-j H:i:s',$time);
    }
    list($YY,$MM,$DD,$Hour,$Min,$Sec) = split('[-, ,:]',$Date);
    $YY = $YY+543;
    $OutPut = $DD." ".Month_TH_Long($MM)." ".$YY;
    return $OutPut;
}

function Date_to_Time($Date){
    list($YY,$MM,$DD,$Hour,$Min,$Sec) = split('[-, ,:]',$Date);
    $OutPut = mktime($Hour, $Min, $Sec, $MM, $DD, $YY);
    return $OutPut;
}

?>