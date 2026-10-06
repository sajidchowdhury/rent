<?php
include_once('function_query.php');

$conn = DB::conn();



$com_profile = SETUP::SETUP_COMPANY('Active');
$get_employeeid = SETUP::ADMIN_SETUP($_SESSION['RENT_COLL_SESS_MEMBER_ID']);
$set_top_bar =  SETUP::TOP_BAR() ;
$numbers = array(0,1,2,3,4,5,6,7,8,9);

$current_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$result = str_replace($numbers,'New', $current_url);
$current_menu_id = FIND::getCurrentMenuId($result);
$current = $current_menu_id['menu_id']; 
$parents_id = $current_menu_id['parents'];

?>



    <input type="hidden" name="title_tag_text" id="title_tag_text" value="<?php print $com_profile['company_short_name'] ;?>">
    <input type="hidden" name="title_company_name" id="title_company_name" value="<?php print $com_profile['company_name'] ;?>">

    <!-- START X-NAVIGATION -->
    <ul class="x-navigation hidden-print" style="">
        <li class="xn-logo">
            <a ><?php print $com_profile['company_name'];?></a>
            <a  class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a  class="profile-mini">
                <img src="upload/employee_photo/<?php print $get_employeeid['photo'];?>" alt="John Doe"/>
            </a>
            <div class="profile">
                <div class="profile-image">
                    <img src="upload/employee_photo/<?php print $get_employeeid['photo'];?>" alt="John Doe"/>
                </div>
                <div class="profile-data">
                    <div class="profile-data-name"><b style="color:red;"><?php print '</b> ' . $get_employeeid['hr_name'];?></div>

                </div>
                <div class="profile-controls">
                    <a href="setup/Employee-Personal-Profile/<?php print  $_SESSION['RENT_COLL_SESS_MEMBER_ID'];?>" class="profile-control-left"><span class="fa fa-info"></span></a>
                    <a  class="profile-control-right"><span class="fa fa-envelope"></span></a>
                </div>
            </div>                                                                        
        </li>

        <?php 
$arr =[];
$query1 = $conn->prepare("SELECT * FROM `menu_list` ORDER BY `sort`  ");
$query1->execute();
$fetch_list1 = $query1->fetchAll(PDO::FETCH_ASSOC);
foreach($fetch_list1 AS $fetch1){

    $access_check = SETUP::MENU_PERMISSION($fetch1['id'],$get_employeeid['id']);
    
    if($access_check['view_check'] == 'Checked'){

    $arr[$fetch1['id']]['menu'] = $fetch1['menu'];
    $arr[$fetch1['id']]['parent_id'] = $fetch1['parent_id'];
    $arr[$fetch1['id']]['menu_link'] = $fetch1['menu_link'];
    $arr[$fetch1['id']]['icon'] = $fetch1['icon'];
    $arr[$fetch1['id']]['status'] = $fetch1['status'];
    $arr[$fetch1['id']]['id'] = $fetch1['id'];

     }else{
        $arr[$fetch1['id']]['menu'] = $fetch1['menu'];
        $arr[$fetch1['id']]['parent_id'] = $fetch1['parent_id'];
        $arr[$fetch1['id']]['menu_link'] = $fetch1['menu_link'];
        $arr[$fetch1['id']]['icon'] = $fetch1['icon'];
        $arr[$fetch1['id']]['status'] = 'NOPERMISSION';
        $arr[$fetch1['id']]['id'] = $fetch1['id'];

     }
   

    
}
$conn = null; 

$html = '';

function buildTreeView($arr, $parent, $level = 0, $prelevel = -1, $current = null, $parents_id = null) {
    
    
    global $html ;

    foreach($arr as $id=>$data){


        
        if($parent == $data['parent_id']){

            if($level > $prelevel){
                if($html == '' ){
                    $html .='<ul><li >';
                }else{
                    $html .='<ul><li >';
                }
                
              }
            
              if($level == $prelevel){
                $html .='</li>';
            }

            if( $parent > $prelevel){
                $style="padding-left:15px;";
            }else{
                $style="";
            }

            if ($id == $current) {

                if($data['status'] == 'NOPERMISSION' ){
                    header('Location: home/Dashboard');
                    die();                
                
                }else{
                    $html .= '<li style="'.$style.'" class="active"><a href="' . $data['menu_link'].'">' . $data['icon'].' <span class="xn-text">' . $data['menu'].'</span></a>';
                }

            } else {

                $aa = explode(",", $parents_id);
                if(in_array($id, $aa)){
                    $html .= '<li style="'.$style.'" class="xn-openable active"><a href="' . $data['menu_link'].'">' . $data['icon'].' <span class="xn-text">' . $data['menu'].'</span></a>';
                }else{
                    $html .= '<li style="'.$style.'" class=""><a href="' . $data['menu_link'].'">' . $data['icon'].' <span class="xn-text">' . $data['menu'].'</span></a>';
                }
            }

           
           

    
        


            if ($level > $prelevel) {
                $prelevel = $level;
            }
            $level++;
            buildTreeView($arr, $id, $level, $prelevel, $current,$parents_id);
            $level--;
       
               

        }
    }
    if($level == $prelevel){
        $html .='</li></ul>';
     }

return  $html;

}


?>

<?php 
print buildTreeView($arr,0,0,-1,$current,$parents_id);?>
</ul>
<!-- END X-NAVIGATION -->


