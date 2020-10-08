<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" /> 

<form method="POST" action="investment.php"> 
    <select name="shares">
      <option value="AIR">AIR NZ</option>
      <option value="KMD">Kathmandu</option>
      <option value="SPY">Smartpay</option>
    </select>
    <input type="submit" value="送出"> 
</form> 
<br>
<?php
$share = $_POST["shares"];
$html = "";
include('simple_html_dom.php');
$opts = array(
  'http'=>array(
    'header'=>"User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53\r\n"
  )
);
$context = stream_context_create($opts);
$url = (string)('https://www.nzx.com/instruments/'.$share);
$html = file_get_html($url, false, $context);
//find the close price or now price
$Findcloseprice = $html->find('div[class=small-12 medium-5 columns] h1',0);
$Findcloseprice = $Findcloseprice->plaintext;
$Findopenprices = $html->find('div[class=small-12 medium-6 large-4 columns] tr');
$arr = array();
foreach($Findopenprices as $row){
    $pricetype = $row->find('td',0)->plaintext;
    $price = $row->find('td',1)->plaintext;
    $arr[$pricetype] = $price;
}
echo '<table><tbody<tr><td>';
echo $row;
//get the open high low close price
echo 'open price = ';
echo $openprice = substr(trim($arr["Open"]),1);
echo '<br>close price = ';
echo $closeprice = substr(trim($Findcloseprice),1);
echo '<br>high price = ';
echo $highprice = substr(trim($arr["High"]) ,1);
echo '<br>low price = ';
echo $lowprice = substr(trim($arr["Low"]),1);
echo '<br>';
//scale value for comparison
$scaleVal = 3;
//line
$upperline = "";
$entity ="";
$downline = "";
//Whether is postive or negative now
if(bccomp($closeprice,$openprice,$scaleVal)=="1" ){
  
  calPosive($openprice,$closeprice,$highprice,$lowprice);
}
else{
  calNegative($openprice,$closeprice,$highprice,$lowprice);
}

function calPosive($openprice,$closeprice,$highprice,$lowprice){
  $scaleVal = 3;
  //upperline calculation
  $upperline = bcsub($highprice,$closeprice,$scaleVal) ;
  echo 'Upperline： '.$upperline.'<br>';
  //Entity calculation
  $entity =  bcsub($closeprice,$openprice,$scaleVal);
  echo 'Entity： '.$entity.'<br>';
  //downline calculation
  $downline = bcsub($openprice,$lowprice,$scaleVal);
  echo 'Downline： '.$downline.'<br>';
  echo '</td><td>';

  if(bccomp($closeprice,$highprice,$scaleVal)=="0")
  {
    if(bccomp($openprice,$lowprice,$scaleVal)=="0" || $downline<0){
      //red1 Strong buyer
      echo '<img src="https://drive.google.com/uc?export=view&amp;id=1q0z0_ZlOQa6W4ZnFpiHXXVw2V9CyLpte" alt="error" width="126" height="383">';
    }
    else{
      //red3 buyer win at the end
      echo '<img src="https://drive.google.com/uc?export=view&amp;id=1zwGIj-aYSaC_fhNZm7tiASLC5Imx0UjL" alt="error" width="126" height="383">';
    }
  }
  else{
    if($downline<=0){
      //red4 rises up after initially falling down, strong buyer
      echo '<img src="https://drive.google.com/uc?export=view&amp;id=1vrtdCmj-_AE-XZpby7UekKcepuMiD4w1" alt="error" width="126" height="383">';
    }
    else{
      //upperline is equal to downline
      if(bccomp($upperline,$downline,$scaleVal) =="0"){
        //red2 buyers are going to be weak
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=18z7ZeRFuW_sPY6HFFvjHDyVuN-mA4Gd8" alt="error" width="126" height="383">';
      }
      else if(bccomp($upperline,$downline,$scaleVal)=="1"){
        //red5 
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1JmmyMjX0UBK-eFC4JVZ4G6gwfMheTN44" alt="error" width="126" height="383">';
      }
      else{
        //red6
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1UjEdfBTjt7CKBHXWNhUGcuXgsSXLVDGK" alt="error" width="126" height="383">';
      }
    }
  }
  echo '</td></tr></tbody></table>';
}

function calNegative($openprice,$closeprice,$highprice,$lowprice){
  $scaleVal = 3;
  //upperline calculation
  $upperline = bcsub($highprice,$$openprice,$scaleVal) ;
  echo 'Upperline： '.$upperline.'<br>';
  //Entity calculation
  $entity =  bcsub($openprice,$closeprice,$scaleVal);
  echo 'Entity： '.$entity.'<br>';
  //downline calculation
  $downline = bcsub($closeprice,$lowprice,$scaleVal);
  echo 'Downline： '.$downline.'<br>';
  echo '</td><td>';
  //for line
  if(bccomp($openprice,$closeprice,$scaleVal) =="0"){
    //for T line
    if(bccomp($highprice,$lowprice,$scaleVal)=="0"){
      //green14
      echo '<img src="https://drive.google.com/uc?export=view&amp;id=1-vnj1Mo6KKOzSuu-KSbImKrwGfuBJQfA" alt="error" width="126" height="383">';
    }
    else if(bccomp($closeprice,$highprice,$scaleVal)=="0"){
      //green12
      echo '<img src="https://drive.google.com/uc?export=view&amp;id=1BdtV5DEibZi1aKbdXIgn82KmFRjpqdVz" alt="error" width="126" height="383">';
    }
    else if(bccomp($closeprice,$lowprice,$scaleVal)=="0"){
      //green13
      echo '<img src="https://drive.google.com/uc?export=view&amp;id=1s_af7NbO9KDFN_eTaIwiPHhX8iercEkL" alt="error" width="126" height="383">';
    }
    //high price and low price are different while close price is equal to the open price
    else{
      if(bccomp($upperline,$downline,$scaleVal)=="0"){
        //green9
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1xQPVnJu33qUGSTlhrJWaAWG4ktXXUZhv" alt="error" width="126" height="383">';
      }
      else if(bccomp($upperline,$downline,$scaleVal)=="1"){
        //green10
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1ZJu1jLQdXmdxONwR03qMkkST2aIFHTAz" alt="error" width="126" height="383">';
      }
      else{
        //green11
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1mbXxs70GZTQSDwFOVpU918VXg-DcEapG" alt="error" width="126" height="383">';
      }
    }
  }
  //there is an entity for k line
  else{
    if(bccomp($closeprice,$lowprice,$scaleVal)=="0"){     
      if(bccomp($openprice,$highprice,$scaleVal)=="0"){  
        //green5
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1vS6qpDqP-bZyHMI2EelzJE_5NXczXW0m" alt="error" width="126" height="383">';
      }
      else{
        //green8
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1FaJXkX32IIOLI39XkayPSEjjXuePscFD" alt="error" width="126" height="383">';
      }
    }
    else{
      if(bccomp($openprice,$highprice,$scaleVal)=="0"){
        //green7
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1Zd3tWvWZJYJlWZ8sZy39ahJZQQ753x72" alt="error" width="126" height="383">';
      }
      else if(bccomp($upperline,$downline,$scaleVal)=="0"){
        //green6
        echo '<img src="https://drive.google.com/uc?export=view&amp;id=1jS9Yef9QGmDQ_Su7lyJtOqaj5cyTNP6u" alt="error" width="126" height="383">';
      }
      else{
        if(bccomp($upperline,$downline,$scaleVal)=="1"){
          //green1
          echo '<img src="https://drive.google.com/uc?export=view&amp;id=1fkSi6byKPIupxBko9WrUjpHkLRxUpIEK" alt="error" width="126" height="383">';
        }
        else{
          //green2
          echo '<img src="https://drive.google.com/uc?export=view&amp;id=149Vdvnh2Q1hTdOuqjDIvO7zcBE9Jk0Mn" alt="error" width="126" height="383">';
        }
      }  
    }
  } 
  echo '</td></tr></tbody></table>';
}


?>
