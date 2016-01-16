<?php
namespace App;
class MrValidateData
{
	public function __construct()
	{
		
	}
	public function htmlDecode($string)
	{
		return html_entity_decode($string,null,"UTF-8");
	}
	public  function Persent($sale,$price){
	
		if(floatval($sale) > floatval($price)){
			return round(100-($price*100)/$sale);
		}elseif(floatval($sale) == floatval($price)){
			return '0';
		}
		return false;
	}
	public function ucfirst_lower($string){
		return	ucfirst(mb_strtolower($string, "UTF-8"));
	}
	public function getWeek($date){
		$week=array("sun"=>"Chủ nhật","mon"=>"Thứ 2","tue"=>"Thứ 3","wed"=>"Thứ 4","thu"=>"Thứ 5","fri"=>"Thứ 6","sat"=>"Thứ 7");
		$x=date("D",strtotime($date));
		foreach ($week as $key=>$value){
			if(strtolower($x)==strtolower($key)){
				return $value;
			}
		}
		return '';
	}
	/*u
	 * data FetchRow in view
	 * name: Name option for rating
	 * view : view count
	 * disable: disable='disable'
	 */
	public function ViewReview($data,$name='home',$view=true,$disable=true){
	 if(!empty($data)){
			$r=array();
			if($disable){
				$d="disabled='disabled'";
			}else{
				$d='';
			}
			$r[5]= ($data['rating'] <=0 )? "checked='checked'" : '' ;
				
			$r[1]=($data['rating']  > 0 && $data['rating'] <=1) ? 	"checked='checked'" :"";
			
			$r[2]=($data['rating']  > 1 && $data['rating'] <=2) ? "checked='checked'" :"";
			
			$r[3]=($data['rating']  > 2 && $data['rating'] <=3) ? "checked='checked'" :"";
			
			$r[4]=($data['rating']  > 3 && $data['rating'] <=4)? "checked='checked'" :"";
			$r[5]=($data['rating']  > 4) ? "checked='checked'" :"";
			
			$review=  " <div class='rating'>
                        <input class=' product-rating' type='radio' name='product-rating-$name-{$data['id']}-{$data['cid_product']}' {$d} {$r[1]}/>
                        <input class=' product-rating' type='radio' name='product-rating-$name-{$data['id']}-{$data['cid_product']}' {$d} {$r[2]}/>
                  	    <input class='  product-rating' type='radio' name='product-rating-$name-{$data['id']}-{$data['cid_product']}' {$d} {$r[3]} />
                       	<input class=' product-rating' type='radio' name='product-rating-$name-{$data['id']}-{$data['cid_product']}' {$d} {$r[4]}/>
                         <input class=' product-rating' type='radio' name='product-rating-$name-{$data['id']}-{$data['cid_product']}' {$d} {$r[5]}/>
                	 </div>
                 ";
				if($view){
					$review=$review." <span class='rating-sum'>({$data['total'] })</span>";
				}
         			
		}else{
			$review=  " <div class='rating '>
	                        <input class=' product-rating' type='radio' name='product-rating-$name- ' disabled='disabled'/>
	                        <input class=' product-rating' type='radio' name='product-rating-$name-' disabled='disabled'/>
	                  	    <input class='  product-rating' type='radio' name='product-rating-$name-' disabled='disabled'/>
	                       	<input class=' product-rating' type='radio' name='product-rating-$name-' disabled='disabled'/>
	                         <input class=' product-rating' type='radio' name='product-rating-$name-' disabled='disabled'/>
                	 </div>
                 ";
				$review=$review." <span class='rating-sum'>(0)</span>";
		}
		return $review;
	}
	public function review_google($name,$data){
		//$total=empty($data['value']['total'])?5:$data['value']['total'];
		//'.round($data['value']['rating']).'
		return '<div style="position: absolute; right: 0px; top: 0px;">
				<span class="review hreview-aggregate">
      <span class="rating">
         <span class="average">5.0</span> stars based on
         <span class="count">35330003</span> reviews
         <span class="votes">35333</span> votes
         
      </span> 
   </span>
					  
	</div>';
		return 		'<div style="position: absolute; right: 0px; top: 0px;display:none;">
	                            <span class="hreview-aggregate"> 
								   <span class="item">
								      <span class="fn">'.$name.'</span></span> Review 
								   <span class="rating">Rating: 
								      <span class="average">5</span> out of <span class="best">5</span> 
								   </span> 
										   based on
								   <span class="count">1000000</span> reviews. 
								</span> 
                   			 </div>';
	}
	/*
	 * data FetchRow in view
	* name: Name option for rating
	* view : view count
	* disable: disable='disable'
	*/
	public function ViewReview_search($data,$name='home',$view=true,$disable=true){
		if(!empty($data['value'])){
			$r=array();
			if($disable){
				$d="disabled='disabled'";
			}else{
				$d='';
			}
			$r[5]= ($data['value']['rating'] <=0 )? "checked='checked'" : '' ;
	
			$r[1]=($data['value']['rating']  > 0 && $data['value']['rating'] <=1) ? 	"checked='checked'" :"";
				
			$r[2]=($data['value']['rating']  > 1 && $data['value']['rating'] <=2) ? "checked='checked'" :"";
				
			$r[3]=($data['value']['rating']  > 2 && $data['value']['rating'] <=3) ? "checked='checked'" :"";
				
			$r[4]=($data['value']['rating']  > 3 && $data['value']['rating'] <=4)? "checked='checked'" :"";
			$r[5]=($data['value']['rating']  > 4) ? "checked='checked'" :"";
				
			$review=  " <div class='rating'>
			<input class=' product-rating' type='radio' name='product-rating-$name-{$data['value']['id']}-{$data['value']['cid_product']}' {$d} {$r[1]}/>
			<input class=' product-rating' type='radio' name='product-rating-$name-{$data['value']['id']}-{$data['value']['cid_product']}' {$d} {$r[2]}/>
			<input class='  product-rating' type='radio' name='product-rating-$name-{$data['value']['id']}-{$data['value']['cid_product']}' {$d} {$r[3]} />
			<input class=' product-rating' type='radio' name='product-rating-$name-{$data['value']['id']}-{$data['value']['cid_product']}' {$d} {$r[4]}/>
			<input class=' product-rating' type='radio' name='product-rating-$name-{$data['value']['id']}-{$data['value']['cid_product']}' {$d} {$r[5]}/>
			</div>
			<span class='icon-arrowdown-black-small'></span>
			";
			$review =$review."<a class='tag-blue listdetailcomment' href='javascript:;'>";
				if(!empty($data['value']['total'])){
					$review =$review."<span class='text-margin'>Có {$data['value']['total']} đánh giá </span> ";
				}
				if(!empty($data['count']['total'])){
					$review =$review." | <span class='text-margin'>Có {$data['count']['total']} câu trả lời</span>";
				}
				$review =$review."</a>";
			}else{
				$review=  " <div class='rating '>
	                        <input class=' product-rating' type='radio' name='product-rating-$name- ' disabled='disabled'/>
	                        <input class=' product-rating' type='radio' name='product-rating-$name-' disabled='disabled'/>
	                  	    <input class='  product-rating' type='radio' name='product-rating-$name-' disabled='disabled'/>
	                       	<input class=' product-rating' type='radio' name='product-rating-$name-' disabled='disabled'/>
	                         <input class=' product-rating' type='radio' name='product-rating-$name-' disabled='disabled'/>
                	 </div>
                 ";
				$review=$review."<span class='icon-arrowdown-black-small'></span><span class='tag-blue listdetailcomment' href='javascript:;'> <span class='text-margin' itemprop='votes'>Có 0 đánh giá </span>  |  <span class='text-margin'>Có 0 câu trả lời</span></span>";
			}
			return $review;
		}
		/*
		 * Hiện rate của đánh giá trong trang chi tiết sản phẩm
		 */
		
		public function ViewReview_Comment($name="reivew_detail",$rate=0){
			$r=array();
				$r[5]= ($rate <=0 )? "checked='checked'" : '' ;
		
				$r[1]=($rate  > 0 && $rate <=1) ? 	"checked='checked'" :"";
		
				$r[2]=($rate  > 1 && $rate <=2) ? "checked='checked'" :"";
		
				$r[3]=($rate  > 2 && $rate<=3) ? "checked='checked'" :"";
		
				$r[4]=($rate > 3 && $rate <=4)? "checked='checked'" :"";
				$r[5]=($rate  > 4) ? "checked='checked'" :"";
		
				$review=  " <div class='rating'>
				<input class=' product-rating' type='radio' name='$name'  {$r[1]} disabled='disabled'/>
				<input class=' product-rating' type='radio' name='$name'  {$r[2]} disabled='disabled'/>
				<input class='  product-rating' type='radio' name='$name' {$r[3]} disabled='disabled'/>
				<input class=' product-rating' type='radio' name='$name' {$r[4]} disabled='disabled'/>
				<input class=' product-rating' type='radio' name='$name'  {$r[5]} disabled='disabled'/>
				</div>
		
				";
				
			return $review;	
				
				
				
		}
	public function htmlEncode($string)
	{
		return htmlentities($string,null,"UTF-8");
	}
	public function filterData($string) {
		$string = preg_replace('#<script.*?</script>#s', '', $string);
		$string = preg_replace('#<\?.*?\?>#s', '', $string);
		$string = str_replace("\\'", "", $string);
		$string = str_replace('\"', "", $string);
		
		$string = Mr_Validate_Data::inject($string);
		$string = Mr_Validate_Data::htmlDecode($string);
		return $string;
	}
	public function asignData($param)
	{
		$data = array();
		
		foreach ($param as $key => $value) {
			if(is_string($value))
			$data[$key] = Mr_Validate_Data::filterData($value);
			else 
			{
				$data[$key] = $value;
			}
		}
		return $data;
	}
	public function asignField($field)
	{
		return Mr_Validate_Data::filterData(Mr_Validate_Data::htmlEncode(Mr_Validate_Data::stripTags($field)));
	}
	public function tranferData($string)
	{
		$trans = array(
					'à'=>'a','á'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a',
					'ă'=>'a','ằ'=>'a','ắ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
					'â'=>'a','ầ'=>'a','ấ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',
					'À'=>'a','Á'=>'a','Ả'=>'a','Ã'=>'a','Ạ'=>'a',
					'Ă'=>'a','Ằ'=>'a','Ắ'=>'a','Ẳ'=>'a','Ẵ'=>'a','Ặ'=>'a',
					'Â'=>'a','Ầ'=>'a','Ấ'=>'a','Ẩ'=>'a','Ẫ'=>'a','Ậ'=>'a',    
					'đ'=>'d','Đ'=>'d',
					'è'=>'e','é'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e',
					'ê'=>'e','ề'=>'e','ế'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
					'È'=>'e','É'=>'e','Ẻ'=>'e','Ẽ'=>'e','Ẹ'=>'e',
					'Ê'=>'e','Ề'=>'e','Ế'=>'e','Ể'=>'e','Ễ'=>'e','Ệ'=>'e',
					'ì'=>'i','í'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',
					'Ì'=>'i','Í'=>'i','Ỉ'=>'i','Ĩ'=>'i','Ị'=>'i',
					'ò'=>'o','ó'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o',
					'ô'=>'o','ồ'=>'o','ố'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
					'ơ'=>'o','ờ'=>'o','ớ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',
					'Ò'=>'o','Ó'=>'o','Ỏ'=>'o','Õ'=>'o','Ọ'=>'o',
					'Ô'=>'o','Ồ'=>'o','Ố'=>'o','Ổ'=>'o','Ỗ'=>'o','Ộ'=>'o',
					'Ơ'=>'o','Ờ'=>'o','Ớ'=>'o','Ở'=>'o','Ỡ'=>'o','Ợ'=>'o',
					'ù'=>'u','ú'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u',
					'ư'=>'u','ừ'=>'u','ứ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
					'Ù'=>'u','Ú'=>'u','Ủ'=>'u','Ũ'=>'u','Ụ'=>'u',
					'Ư'=>'u','Ừ'=>'u','Ứ'=>'u','Ử'=>'u','Ữ'=>'u','Ự'=>'u',
					'ỳ'=>'y','ý'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
					'Y'=>'y','Ỳ'=>'y','Ý'=>'y','Ỷ'=>'y','Ỹ'=>'y','Ỵ'=>'y'
				  );
  		$string = strtr(Mr_Validate_Data::htmlDecode($string), $trans);
  		$string = Mr_Validate_Data::filterData($string);


  		return $string;		
	}

	public  function formod($a)
	{
		
		if(!empty($a)){
			$strong=substr($a, 0,1);
			for($i=0;$i<strlen($a);$i++){
				if($i!=0)
				$strong=$strong."0";
			}
			return $strong;
		}else{
			return '';
		}
	}
	public static function tranferData2($string)
	{
		$trans = array(
					'à'=>'a','á'=>'a','á'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a',
					'ă'=>'a','ằ'=>'a','ắ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
					'â'=>'a','ầ'=>'a','ấ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',
					'À'=>'a','Á'=>'a','Ả'=>'a','Ã'=>'a','Ạ'=>'a',
					'Ă'=>'a','Ằ'=>'a','Ắ'=>'a','Ẳ'=>'a','Ẵ'=>'a','Ặ'=>'a',
					'Â'=>'a','Ầ'=>'a','Ấ'=>'a','Ẩ'=>'a','Ẫ'=>'a','Ậ'=>'a',    
					'đ'=>'d','Đ'=>'d',
					'è'=>'e','é'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e',
					'ê'=>'e','ề'=>'e','ế'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',
					'È'=>'e','É'=>'e','Ẻ'=>'e','Ẽ'=>'e','Ẹ'=>'e',
					'Ê'=>'e','Ề'=>'e','Ế'=>'e','Ể'=>'e','Ễ'=>'e','Ệ'=>'e',
					'ì'=>'i','í'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',
					'Ì'=>'i','Í'=>'i','Ỉ'=>'i','Ĩ'=>'i','Ị'=>'i',
					'ò'=>'o','ó'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o',
					'ô'=>'o','ồ'=>'o','ố'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
					'ơ'=>'o','ờ'=>'o','ớ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',
					'Ò'=>'o','Ó'=>'o','Ỏ'=>'o','Õ'=>'o','Ọ'=>'o',
					'Ô'=>'o','Ồ'=>'o','Ố'=>'o','Ổ'=>'o','Ỗ'=>'o','Ộ'=>'o',
					'Ơ'=>'o','Ờ'=>'o','Ớ'=>'o','Ở'=>'o','Ỡ'=>'o','Ợ'=>'o',
					'ù'=>'u','ú'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u',
					'ư'=>'u','ừ'=>'u','ứ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',
					'Ù'=>'u','Ú'=>'u','Ủ'=>'u','Ũ'=>'u','Ụ'=>'u',
					'Ư'=>'u','Ừ'=>'u','Ứ'=>'u','Ử'=>'u','Ữ'=>'u','Ự'=>'u',
					'ỳ'=>'y','ý'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
					'Y'=>'y','Ỳ'=>'y','Ý'=>'y','Ỷ'=>'y','Ỹ'=>'y','Ỵ'=>'y'
				  );
  		$string = strtr(html_entity_decode($string,null,"UTF-8"), $trans);
  		return $string;		
	}
	
	public function stripTags($string, $allowed_tags = null, $allowed_attributes = null)
	{
		$string = Mr_Validate_Data::filterData($string);
		$html_filter = new Zend_Filter_StripTags($allowed_tags, $allowed_attributes);
		$string = $html_filter->filter($string);
		$string = trim($string, " \n\t");
		return $string;
	}
	public function toLower($string, $encode = "UTF-8")
	{
		return mb_strtolower($string, $encode);
	}
	
	public static function toLower2($string, $encode = "UTF-8")
	{
		return mb_strtolower($string, $encode);
	}
	public function toUpper($string, $encode = "UTF-8")
	{
		return mb_strtoupper($string, $encode);
	}
	public static function toUpper2($string, $encode = "UTF-8")
	{
		return mb_strtoupper($string, $encode);
	}
	
	public function toAlias($string)
	{
		$tmp = array("~","`","!","@","#","$","%","^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">",".","?","/");
		
		$string = Mr_Validate_Data::tranferData($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp,"",$string);
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".$key;
		}
		return Mr_Validate_Data::toLower(substr($string, 1));
	}
	
	public static  function toAlias2($string)
	{
		$tmp = array("”","–","~","`","!","@","#","$","%",'%',"^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">",".","?","/");
		$string=mb_strtolower($string,"UTF-8");
		$string = Mr_Validate_Data::tranferData2($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp,"",$string);
		
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".preg_replace('/[^A-Za-z0-9\-]/', '', $key);
		}
		$string = str_replace("-–-","",$string);
	
		return Mr_Validate_Data::toLower2(substr($string, 1));
	}
	public static  function toAlias3($string)
	{
		$tmp = array("”","–","~","`","!","@","#","$","%",'%',"^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">",".","?","/");
		$string=mb_strtolower($string,"UTF-8");
		$string = Mr_Validate_Data::tranferData2($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp,"",$string);
		$string=substr($string,0,15);
		
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".preg_replace('/[^A-Za-z0-9\-]/', '', $key);
		}
		$string = str_replace("-–-","",$string);
	
		return Mr_Validate_Data::toLower2(substr($string, 1));
	}

	public function toAliasImages($string)
	{
		$tmp = array("~","`","!","@","#","$","%","^","&","*","(",")","-","_","=","+","{","[","]","}","|","\\",":",";","'","\"","<",",",">","?","/");
		
		$string = Mr_Validate_Data::tranferData($string);
		$string = strip_tags($string);
		$string = trim($string, " \n\t.");
		$string = str_replace($tmp," ",$string);
		
		$arr = explode(" ", $string);
		
		$string = "";
		foreach ($arr as $key)
		{
			if(!empty($key))
				$string.= "-".$key;
		}
		return Mr_Validate_Data::toLower(substr($string, 1));
	}
	public static function checkEmailType($email_address, $requied = false)
	{
		if(empty($email_address))
		{
			return $requied;
		}
		else 
		{
			 return preg_match('/^[^@]*@[^@]*\.[^@]*$/', $email_address);
		}
		
	}
	public static function checkPhone($phone){
			if(empty($phone)){
				return false;
			}
				if(preg_match("/^([1]-)?[0-9]{4}[0-9]{3}[0-9]{3}$/i", $phone) || preg_match("/^([1]-)?[0-9]{4} [0-9]{3} [0-9]{3}$/i", $phone) || preg_match("/^([1]-)?[0-9]{4}-[0-9]{3}-[0-9]{3}$/i", $phone) ) {
				   return true;
				}
				return false;
	}
	
	public	function checkDate($string)
	{
		$check = explode('/',$string);
		
		if(count($check)!=3)
		{
			return false;
		}
		if($check[1]<1 || $check[1]>12)
		{
			return false;
		}
		else 
		{
			$check[1] = (int) $check[1];
		}

		if ($check[2]<1)
		{
			return false;
		}
		else 
		{
			$check[2] = (int) $check[2]%100;
		}
		
		$thang = array();
		$thang[1] = 31;
		$thang[2] =28;
		$thang[3] =31;
		$thang[4] =30;
		$thang[5] =31;
		$thang[6] =30;
		$thang[7] =31;
		$thang[8] =31;
		$thang[9] =30;
		$thang[10] =31;
		$thang[11] =28;
		$thang[12] =31;
		if($check[2]%4 == 0)
		{
			$thang[2] =29;
		}
		if($check[0]<1)
		{
			return false;
		}
		else
		{	
			$check[0] = (int) $check[0];
			if($check[0]<=$thang[$check[1]])
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		return false;
	}
	
	public function check_File($file_name,$extent_file){
		$extent_file = strtolower($extent_file);//"jpg|gif";	
		$file_name = strtolower($file_name);
		if(!preg_match("/\\.(" . $extent_file . ")$/",$file_name)){ 
    		 return false;
		}
	 		return true;
	}
	public static function check_File_2($file_name,$extent_file){
		$extent_file = strtolower($extent_file);//"jpg|gif";
		$file_name = strtolower($file_name);
		if(!preg_match("/\\.(" . $extent_file . ")$/",$file_name)){
			return false;
		}
		return true;
	}
	public static function delete_directory($dirname) {
	   if (is_dir($dirname))
	      $dir_handle = opendir($dirname);
	   if (!$dir_handle)
	      return false;
	   while($file = readdir($dir_handle)) {
	      if ($file != "." && $file != "..") {
	         if (!is_dir($dirname."/".$file))
	            unlink($dirname."/".$file);
	         else
	            Mr_Validate_Data::delete_directory($dirname.'/'.$file);    
	      }
	   }
	   closedir($dir_handle);
	   rmdir($dirname);
	   return true;
	}
	public static function recurse_copy($src,$dst,$id=1,$id_old=1) {
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					Mr_Validate_Data::recurse_copy($src . '/' . $file,$dst . '/' .$file ,$id, $id_old);
				}
				else {
					$file_x=str_replace("product", "color", str_replace($id_old, $id, $file));
					copy($src . '/' . $file,$dst . '/' . $file_x );
				}
			}
		}
		closedir($dir);
		Mr_Validate_Data::delete_directory($dir);
		return true;
	}
	public static function cleare_directory($dirname) {
	   if (is_dir($dirname))
	      $dir_handle = opendir($dirname);
	   if (!$dir_handle)
	      return false;
	   while($file = readdir($dir_handle)) {
	      if ($file != "." && $file != "..") {
	         if (!is_dir($dirname."/".$file))
	            unlink($dirname."/".$file);
	         else
	            Mr_Validate_Data::delete_directory($dirname.'/'.$file);    
	      }
	   }
	   closedir($dir_handle);
	   return true;
	}
	public static function createFolder($dir){
		if(!file_exists($dir)){
			mkdir($dir, 0777);
		}
		return true;
	}
	
	public static function checkLength($string, $min, $max){
		$len = mb_strlen($string,"UTF-8");
		if($len<$min||$len>$max){
			return false;
		}
		return true;
	}
	
	public static function checkRegexp($string, $regexp){
		
		return preg_match($regexp, $string);
		
	}
	
	public static function comPare($str1, $str2){
		if($str1!=$str2){
			return false;
		}
		return true;
	}
	public static function showImage($id, $noimgpath = "noimg.gif?"){
		if(is_file(PICTURE_PATH."user/avatar_aff/".$id.".png")){
			return PICTURE_URL."user/avatar_aff/".$id.".png";
		}
		else{
			return PICTURE_URL."user/user.png";
		}
	}
	
	public static function checkEmpty($val){
		return empty($val);
	}
	public static function checkEmptyArray($arr=array()){
		foreach($arr as $a){
			return empty($a);
		}
	}
	
	public static function showTime($stringDate){
		$restr = "";
		$endDate = explode(" ", $stringDate);
		$nowDate = explode(" ", date("Y-m-d H:i:s"));
		$arrendDate = explode("-", $endDate[0]);
		$arrendH = explode(":", $endDate[1]);
		$arrnowDate = explode("-", $nowDate[0]);
		$arrnowH = explode(":", $nowDate[1]);
		if($endDate[0] == $nowDate[0]){
			$time = mktime($arrnowH[0],$arrnowH[1],$arrnowH[2],$arrnowDate[1],$arrnowDate[2],$arrnowDate[0]) - mktime($arrendH[0],$arrendH[1],$arrendH[2],$arrnowDate[1],$arrnowDate[2],$arrnowDate[0]);

			$h = (int)($time/3600);
			$i = number_format(($time-($h*3600))/60);
			if(!$h){
				
				$i = $i==0?1:$i;
				$restr.=$i." phút trước";
			}
			else{
				$restr.=$h." tiếng ".$i." phút trước";
			}
		}
		else{
			$restr.= $arrendH[0]." giờ ".$arrendH[1]." phút ngày ".$arrendDate[2]."/".$arrendDate[1]."/".$arrendDate[0];
		}
		return $restr;
	}
	public static function showThumb($dir){
		$arr = scandir(USERDATA_PATH.$dir."/thumbs/");
		$c = count($arr);
		if($c>2){
			return USERDATA_URL.$dir."/thumbs/".$arr[rand(2, $c-1)];
		}
		else{
			return "/public/noimg.gif?";
		}
	}
	
	public static function dateToNumber($date){
		$arr = explode(" ", $date);
		$arr1 = explode("-", $arr[0]);
		$arr2 = explode(":", $arr[1]);
		return mktime($arr2[0],$arr2[1],$arr2[2],$arr1[1],$arr1[2],$arr1[0]);
	}
	public static function subString($string,$len){
		$string = html_entity_decode(strip_tags($string),null,"UTF-8");
		if(mb_strlen($string,"UTF-8")<=$len){
			return $string;
		}
		else{
			return mb_substr($string, 0,($len-3),"UTF-8")."...";
		}
	}
	public function randd($option=12){
		 $int = rand(0,10);
	    $a_z = "01234567891";
	    $rand_letter = $a_z[$int];
	    for($i=0;$i<$option;$i++){
	    	 $int1 = rand(0,10);
	    	$rand_letter.= $a_z[$int1];
	    }
	    return $rand_letter;
	}
	public function randomNumber($option=17){
		 $int = rand(0,51);
	    $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $rand_letter = $a_z[$int];
	    for($i=0;$i<$option;$i++){
	    	 $int1 = rand(0,51);
	    	$rand_letter.= $a_z[$int1];
	    }
	    return $rand_letter;
	}
	public static function toPice($pice) {
		if(is_numeric($pice)){
			return number_format($pice,0,".",".")." Đ";
		}
		else{
			return "Liên hệ";
		}
	}
	
	public static function toPrice($pice) {
		if(is_numeric($pice)){
			return number_format($pice,0,".",".")." Đ";
		}
		else{
			return "0";
		}
	}
	
	public static  function uploadProduct($source,$filename){
		move_uploaded_file($source, PRODUCTS_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(PRODUCTS_PATH.$filename);
		$thumnalbig->resize(230,230);
		$thumnalbig->save(PRODUCTS_PATH."big/".$filename);
		
		$thumnalsmall=$mr->create(PRODUCTS_PATH.$filename);
		$thumnalsmall->resize(100,100);
		$thumnalsmall->save(PRODUCTS_PATH."small/".$filename);
		return true;
		
	}
	public static  function uploadProduct_New($source,$multipleimage,$id_product){
		Mr_Validate_Data::createFolder(PRODUCTS_PATH."product".$id_product);
		Mr_Validate_Data::createFolder(PRODUCTS_PATH."product".$id_product."/small/");
		Mr_Validate_Data::createFolder(PRODUCTS_PATH."product".$id_product."/big/");
		
		$path=PRODUCTS_PATH."product".$id_product;
		
		move_uploaded_file($source, $path."/product_{$id_product}.png");
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create($path."/product_{$id_product}.png");
		$thumnalbig->resize(230,230);
		$thumnalbig->save($path."/big/product_{$id_product}.png");
		
		$thumnalsmall=$mr->create($path."/product_{$id_product}.png");
		$thumnalsmall->resize(100,100);
		$thumnalsmall->save($path."/small/product_{$id_product}.png");
		
		if(!empty($multipleimage['name'][0])){
		for($j=0;$j<count($multipleimage['name']);$j++)
            {
      			move_uploaded_file($multipleimage['tmp_name'][$j], $path."/product_{$id_product}_{$j}.png");
      			$thumnalbig=$mr->create($path."/product_{$id_product}_{$j}.png");
				$thumnalbig->resize(230,230);
				$thumnalbig->save($path."/big/product_{$id_product}_{$j}.png");
				
				$thumnalsmall=$mr->create($path."/product_{$id_product}_{$j}.png");
				$thumnalsmall->resize(100,100);
				$thumnalsmall->save($path."/small/product_{$id_product}_{$j}.png");
      		}
		}
		
		return true;
		
	}
    
    public static function uploadProduct_lcd($source,$multipleimage,$id_product,$avatar=null,$pathIn=null){
        if(empty($pathIn)){
            $pathIn = PRODUCTS_PATH;
        }
		Mr_Validate_Data::createFolder($pathIn."product".$id_product);
		Mr_Validate_Data::createFolder($pathIn."product".$id_product."/small/");
		Mr_Validate_Data::createFolder($pathIn."product".$id_product."/big/");
		
		$path=$pathIn."product".$id_product;
		$mr=new Mr_ThumbLib();
        
        $filecount = 0;
        $filecount = count(glob($path."/*.png"));
        
		if(!empty($multipleimage)){
            foreach($multipleimage as $k=>$v){
                if($v):
                $k=$k+$filecount+$filecount;
                if(!empty($avatar)){
                    $k = 1;

                    if(is_file(PICTURE_PATH."tmp/product_{$id_product}_120_120.jpg") ){
                    	  @unlink(PICTURE_PATH."tmp/product_{$id_product}_120_120.jpg");
                    } 
                      if(is_file(PICTURE_PATH."tmp/product_{$id_product}_140_140.jpg") ){
                    	  @unlink(PICTURE_PATH."tmp/product_{$id_product}_140_140.jpg");
                    } 
                  


                }
                //$f = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$v;
                $f = $source.$v;
                $t = $path."/product_{$id_product}_{$k}.png";
                copy($f,$t);
                //unlink($f);                
                
      			$thumnalbig=$mr->create($path."/product_{$id_product}_{$k}.png");
				$thumnalbig->resize(230,230);
				$thumnalbig->save($path."/big/product_{$id_product}_{$k}.png");
				
				$thumnalsmall=$mr->create($path."/product_{$id_product}_{$k}.png");
				$thumnalsmall->resize(100,100);
				$thumnalsmall->save($path."/small/product_{$id_product}_{$k}.png");
                                
                endif;
            }
        }
         file_get_contents("http://{$_SERVER['SERVER_NAME']}/product/image?id={$id_product}&tag=&remove=1");
		return true;
		
	}
	public static function uploadProduct_lcd_supplier($source,$name,$id_product){
		if(!is_file($source)) return false;
		
			Mr_Validate_Data::createFolder(SUPPLIER_PRODUCT_PATH."product".$id_product);
			Mr_Validate_Data::createFolder(SUPPLIER_PRODUCT_PATH."product".$id_product."/small/");
			Mr_Validate_Data::createFolder(SUPPLIER_PRODUCT_PATH."product".$id_product."/big/");
			$mr=new Mr_ThumbLib();
				copy($source,SUPPLIER_PRODUCT_PATH."product".$id_product."/".$name);			
				$thumnalbig=$mr->create(SUPPLIER_PRODUCT_PATH."product".$id_product."/".$name);
				$thumnalbig->resize(230,230);
				$thumnalbig->save(SUPPLIER_PRODUCT_PATH."product".$id_product."/big/".$name);
	
				$thumnalsmall=$mr->create(SUPPLIER_PRODUCT_PATH."product".$id_product."/".$name);
				$thumnalsmall->resize(100,100);
				$thumnalsmall->save(SUPPLIER_PRODUCT_PATH."product".$id_product."/small/".$name);
				
		return true;
	}
	public static function uploadColorSupplier($source,$name,$id_product){
	
		Mr_Validate_Data::createFolder(SUPPLIER_COLOR_PATH."product".$id_product);
		Mr_Validate_Data::createFolder(SUPPLIER_COLOR_PATH."product".$id_product."/small/");
		Mr_Validate_Data::createFolder(SUPPLIER_COLOR_PATH."product".$id_product."/big/");
		$mr=new Mr_ThumbLib();
		copy($source,SUPPLIER_COLOR_PATH."product".$id_product."/".$name);
		$thumnalbig=$mr->create(SUPPLIER_COLOR_PATH."product".$id_product."/".$name);
		$thumnalbig->resize(230,230);
		$thumnalbig->save(SUPPLIER_COLOR_PATH."product".$id_product."/big/".$name);
	
		$thumnalsmall=$mr->create(SUPPLIER_COLOR_PATH."product".$id_product."/".$name);
		$thumnalsmall->resize(100,100);
		$thumnalsmall->save(SUPPLIER_COLOR_PATH."product".$id_product."/small/".$name);
	
		return true;
	}
    public static function uploadColor_lcd($id_color,$image,$num=null,$path=null,$source=null){
        if(empty($path)){
            $path = COLOR_PATH;    
        }
        $path .= "color".$id_color."/"; 
        Mr_Validate_Data::createFolder($path);
		Mr_Validate_Data::createFolder($path."small/");
		Mr_Validate_Data::createFolder($path."big/");
		$mr=new Mr_ThumbLib();
        if(!empty($source)){
            $f = $source.$image;
        }else{
            $f = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$image;    
        }
        $name = "color_{$id_color}_1";
        if(!empty($num)){
            $name = "color_{$id_color}_{$num}";            
        }
        $t = $path.$name.".png";
        copy($f,$t);
        
        $thumnalbig=$mr->create($t);
		$thumnalbig->resize(230,230);
		$thumnalbig->save($path."big/{$name}.png");
				
		$thumnalsmall=$mr->create($t);
		$thumnalsmall->resize(100,100);
		$thumnalsmall->save($path."small/{$name}.png");
          
		return true;
	}
    
    public static function get_image_color_lcd($id,$option=null,$num=null,$pathIn=null,$linkIn=null){
        if(empty($pathIn)){
            $pathIn = COLOR_PATH."color{$id}/";
        }
        
        if(empty($linkIn)){
            $linkIn = COLOR_URL."color{$id}/";
        }
        
        $name = "color_{$id}_1";
        if(!empty($num)){
            $name = "color_{$id}_{$num}";            
        }
        
		if(empty($option)){
			$path="{$name}.png";
		}else{
			$path="{$option}/{$name}.png";
		}
                
		if(is_file($pathIn.$path)){
			return  $linkIn.$path;
		}else{
            return "/public/noimg.gif?";		   
		}	
	}
    
	public static function remove_all_ImageProduct_New($id_product){
		Mr_Validate_Data::delete_directory(PRODUCTS_PATH."product".$id_product);
	}
	public static function remove_one_ImageProduct_New($id_product){
		$path=PRODUCTS_PATH."product".$id_product;
		unlink($source, $path."/product_{$id_product}.png");
		unlink($source, $path."/big/product_{$id_product}.png");
		unlink($source, $path."/small/product_{$id_product}.png");
	}
	public static function get_all_product_new($id_product,$option=''){
		$data=array();
		if($option==''){
			$path="product".$id_product;
		}else{
			$path="product".$id_product."/{$option}";
		}
		
		@$arr = scandir(PRODUCTS_PATH.$path);
		$c = count($arr);
		if($c>2){
			$data["1"]="http://static.dienmaycholon.vn/product/".$path."/product_$id_product.png";
			for($i=2;$i<$c;$i++)
			$data[$i]= "http://static.dienmaycholon.vn/product/".$path."/product_$id_product.png";
		}
		else{
			$data=array("1"=> "/public/noimg.gif?");
		}
		return $data;
	}
	public static function get_image_product_new($id_product,$option=''){
		if($option==''){
			$path="product".$id_product;
		}else{
			$path="product".$id_product."/{$option}";
		}
		if(is_file(PRODUCTS_PATH.$path."/product_$id_product.png")){
			return  "http://static.dienmaycholon.vn/product/".$path."/product_$id_product.png";
		}
		return "/public/noimg.gif";
			
	}
    
    public static function get_image_product_lcd($id_product,$option='',$pathIn=null,$linkIn=null,$name=null){
        if(empty($pathIn)){
            $pathIn = PRODUCTS_PATH;
        }
        
        if(empty($linkIn)){
            $linkIn = "http://static.dienmaycholon.vn/product/";
        }
        if(empty($name)){
            $name = "product";
        }
		if($option==''){
			$path=$name.$id_product;
		}else{
			$path=$name.$id_product."/{$option}";
		}
		
		if(is_file($pathIn.$path."/{$name}_{$id_product}_1.png")){
			//if(empty($pathIn)){
			//	return "/product/image?id={$id_product}&tag=$option&";
			//}
			return $linkIn.$path."/{$name}_{$id_product}_1.png?name_product";
// 			$TTCache=new Mr_Cache_ImageCache();
// 			$TTCache->cached_image_directory =  PUBLIC_PATH."/cache_images";
// 			$cached_src_one = $TTCache->cache( $pathIn.$path."/{$name}_{$id_product}_1.png" );
// 			return $cached_src_one;

			//"/public/cache_images/c01bf3b02401e2de5737904a95247531.jpeg";
			
		
			
		}
		return "/public/noimg.gif?";
			
	}
	public static function get_image_product_lcd_home($id_product,$option='',$width=null,$height=null){
		
		if(!empty($width) && !empty($height)){
			if(!is_file(PICTURE_PATH."tmp/product_{$id_product}_{$width}_{$height}.jpg") 
				&& is_file(PRODUCTS_PATH."/product".$id_product."/product_{$id_product}_1.png")){
				
				$mr=new Mr_ThumbLib();
				$thumnalbig=$mr->create(PRODUCTS_PATH."/product".$id_product."/product_{$id_product}_1.png");
				$thumnalbig->resize($width,$height);
				$thumnalbig->save(PICTURE_PATH."tmp/product_{$id_product}_{$width}_{$height}.jpg");
			}
			if(is_file(PICTURE_PATH."tmp/product_{$id_product}_{$width}_{$height}.jpg")){
				return "http://static.dienmaycholon.vn/tmp/product_{$id_product}_{$width}_{$height}.jpg";	
			}
			
		}
		
		
		if(empty($name)){
			$name = "product";
		}
		if($option==''){
			$path=$name.$id_product;
		}else{
			$path=$name.$id_product."/{$option}";
		}
	
		if(is_file(PRODUCTS_PATH.$path."/{$name}_{$id_product}_1.png")){
			//if(empty($pathIn)){
			//return "/product/image?id={$id_product}&tag=$option&";
			//}
			return "http://static.dienmaycholon.vn/product/".$path."/{$name}_{$id_product}_1.png";
			// 			$TTCache=new Mr_Cache_ImageCache();
			// 			$TTCache->cached_image_directory =  PUBLIC_PATH."/cache_images";
			// 			$cached_src_one = $TTCache->cache( $pathIn.$path."/{$name}_{$id_product}_1.png" );
			// 			return $cached_src_one;
	
			//"/public/cache_images/c01bf3b02401e2de5737904a95247531.jpeg";
				
	
				
		}
		return "/public/noimg.gif?";
			
	}
	
	public static function TTCacheImage($cache,$path){
		if(is_file($path)&& is_object($cache)){
				return $cache->cache($path);
			
		}
		return "/public/noimg.gif?";
	}
	public static function get_image_product_lcd_cate($cache,$id_product,$option=''){
	
		
	
	
		if(empty($name)){
			$name = "product";
		}
		if($option==''){
			$path=$name.$id_product;
		}else{
			$path=$name.$id_product."/{$option}";
		}
	
		if(is_file(PRODUCTS_PATH.$path."/{$name}_{$id_product}_1.png")){
			$value=PRODUCTS_PATH.$path."/{$name}_{$id_product}_1.png";
			return $cache->cache($value);
	
	
	
		}
		return "/public/noimg.gif?";
			
	}
	public static function get_image_product_all_lcd($id,$path=null,$name=null,$opt=null){
	
		$data=array();
		
        if(empty($path)){
            $path = "http://m.dienmaycholon.vn/img/product/";
        }
        if(empty($opt)){
            $opt = "small"; 
        }
        if(empty($name)){
            $name = "product";
        }
		@$arr = scandir($path.$name.$id."/{$opt}/");
		$c = count($arr);
		
		if($c>=2){
			foreach($arr as $a){
				if (Mr_Validate_Data::check_File_2($a, "png") ) {
					$data[]=$a;
				}
			}
			return $data;
		}
		return false;
			
	}
	public static function get_image_color_all_lcd($id_product){
	
		$data=array();
	
		@$arr = scandir(COLOR_PATH."color".$id_product."/small");
		$c = count($arr);
	
	
		if($c>2){
			foreach($arr as $a){
				if (Mr_Validate_Data::check_File_2($a, "png") ) {
					$data[]=$a;
				}
			}
			return $data;
		}
		return false;
			
	}
	public static function getAll_Color_Supplier($id_color){
	
		$data=array();
	
		@$arr = scandir(SUPPLIER_COLOR_PATH."product".$id_color."/small/");
		$c = count($arr);
	
	
		if($c>2){
			foreach($arr as $a){
				if (Mr_Validate_Data::check_File_2($a, "png") ) {
					$data[]=$a;
				}
			}
			return $data;
		}
		return false;
			
	}
	public static  function uploadCate($source,$filename){
		move_uploaded_file($source, CATE_PATH.$filename);
		return true;
	}
	public static function uploadCustomer($source,$filename){
		move_uploaded_file($source, CUSTOMER_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(CUSTOMER_PATH.$filename);
		$thumnalbig->resize(230,230);
		$thumnalbig->save(CUSTOMER_PATH."big/".$filename);		
		$thumnalsmall=$mr->create(CUSTOMER_PATH.$filename);
		$thumnalsmall->resize(49,49);
		$thumnalsmall->save(CUSTOMER_PATH."small/".$filename);
		return true;
	}
	public static function removeCustomer($filename){
		unlink(CUSTOMER_PATH.$filename);
		unlink(CUSTOMER_PATH."big/".$filename);
		unlink(CUSTOMER_PATH."small/".$filename);
	}
	public static function removeProduct($filename){
		unlink(PRODUCTS_PATH.$filename);
		unlink(PRODUCTS_PATH."big/".$filename);
		unlink(PRODUCTS_PATH."small/".$filename);
	}
	public static function uploadSlideshow($filename,$tmp){
		$mr=new Mr_ThumbLib();
		$thum=$mr->create($tmp);
		$thum->resize(800,273);
		$thum->save(SLIDE_PATH.$filename);
	}

	public static function loadCache($hour=3600){
		$frontendOptions = array('lifetime' => $hour, 'automatic_serialization' => true);// cache lifetime of 1 hours
		$backendOptions = array("cache_dir"=>"tmp/");
		return Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
	}
	function getIP()
	{
		//get ip
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
 	public static  function uploadSlideShows($source,$filename,$path){
 	  
    }    
    
	public static  function uploadNews($source,$filename){
		move_uploaded_file($source, NEWS_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(NEWS_PATH.$filename);
		$thumnalbig->resize(276,182);
		$thumnalbig->save(NEWS_PATH."big/".$filename);
		
		$thumnalsmall=$mr->create(NEWS_PATH.$filename);
		$thumnalsmall->resize(85,57);
		$thumnalsmall->save(NEWS_PATH."small/".$filename);
        
        $thumnalsmall2=$mr->create(NEWS_PATH.$filename);
		$thumnalsmall2->resize(271,113);
		$thumnalsmall2->save(NEWS_PATH."small2/".$filename);        
		return true;		
	}
    public static  function uploadPopup($source,$filename){
		move_uploaded_file($source, POPUP_PATH.$filename);
		return true;		
	}
    
    public static  function upload($source,$filename,$path,$path2=0,$path3=0){
		move_uploaded_file($source, $path.$filename);
        if(!empty($path2)) move_uploaded_file($source, $path2.$filename);
        if(!empty($path3)) move_uploaded_file($source, $path3.$filename);
		return true;		
	}
    public static function remove_uploadPopup($id_product){
		unlink(POPUP_PATH."/dienmay_{$id_product}.png");
	}
    public static function remove_banner($id_product){
		unlink(BANNER_PATH."/image1_{$id_product}.png");
        unlink(BANNER_PATH."/image2_{$id_product}.png");
        unlink(BANNER_PATH."/image3_{$id_product}.png");
        unlink(BANNER_PATH."/image4_{$id_product}.png");
	}
    public static function remove_upload($path=0,$path2=0,$path3=0,$id_product,$path4=0){
        if(!empty($path)) unlink($path."/dienmay_{$id_product}.png");
		if(!empty($path2)) unlink($path2."/dienmay_{$id_product}.png");
        if(!empty($path3)) unlink($path3."/dienmay_{$id_product}.png");
        if(!empty($path4)) unlink($path4."/icon_{$id_product}.png");
	}
    public static  function uploadStore($source,$filename){
		move_uploaded_file($source, STORE_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(STORE_PATH.$filename);
		$thumnalbig->resize(230,172);
		$thumnalbig->save(STORE_PATH."big/".$filename);
		
		$thumnalsmall=$mr->create(STORE_PATH.$filename);
		$thumnalsmall->resize(49,36);
		$thumnalsmall->save(STORE_PATH."small/".$filename);
		return true;		
	}
    public static  function uploadbarner($source,$filename, $w_thumb, $h_thumb, $w_thumb_small, $h_thumb_small){
		move_uploaded_file($source, BARNER_PATH.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create(BARNER_PATH.$filename);
		$thumnalbig->resize($w_thumb,$h_thumb);
		$thumnalbig->save(BARNER_PATH."big/".$filename);
		
		$thumnalsmall=$mr->create(BARNER_PATH.$filename);
		$thumnalsmall->resize($w_thumb_small,$h_thumb_small);
		$thumnalsmall->save(BARNER_PATH."small/".$filename);
		return true;
	}
    
     public static  function uploadfull($path,$source,$filename, $w_thumb, $h_thumb, $w_thumb_small, $h_thumb_small){
		move_uploaded_file($source, $path.$filename);
		$mr=new Mr_ThumbLib();
		$thumnalbig=$mr->create($path.$filename);
		$thumnalbig->resize($w_thumb,$h_thumb);
		$thumnalbig->save($path."big/".$filename);
		
		$thumnalsmall=$mr->create($path.$filename);
		$thumnalsmall->resize($w_thumb_small,$h_thumb_small);
		$thumnalsmall->save($path."small/".$filename);
		return true;
	}    
    public static function log($ip,$controller,$action,$userid, $id_pro=0, $saleprice_old=0, $saleprice_new=0, $price_old=0, $price_new=0,$id_promo_promotion=0)
    {
        $log_s = new Application_Model_DbTable_Tm_Log();
        $text_action = $controller."-".$action;
        $date = date("Y-m-d H:i:s");
        if(!empty($ip) && !empty($controller) && !empty($action) && !empty($userid) && !empty($date))
        {
            $log = $log_s->fetchNew();
            $log->user_id = $userid;
            $log->date = $date;
            $log->action = $text_action;
            $log->ip = $ip;
            $log->sap_code = $id_pro;
            $log->saleprice_old = $saleprice_old;
            $log->saleprice_new = $saleprice_new;
            $log->price_old = $price_old;
            $log->price_new = $price_new; 
			$log->id_promo_promotion= $id_promo_promotion;
            $log->save();
            return true;
        }
    }
    
    public function getRate($rate,$litmi){
    	if($rate==$litmi){
    		return "checked='checked'";
    	}
    	return '';
    	
    }
    public static function uploadbank_promotion($source,$id_product,$filename){
        if(empty($pathIn)){
            $pathIn = BANKPROMOTION_PATH;
        }
		Mr_Validate_Data::createFolder($pathIn."bankpro".$id_product);
		
		$path=$pathIn."bankpro".$id_product;                
        move_uploaded_file($source, $path.'/'.$filename);

		return true;
		
	}
	//sql injection
	public static function inject($string){
		//[ \ ^ $ . | ? * + ( )
		
		$check= array("`","!","^","+","{","[","]","}","|",";");

		return str_replace($check,"",$string);
	}
}
?>