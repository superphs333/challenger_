<head>
    <!-- 외부 css 불러오기 -->
    <link rel="stylesheet" type="text/css" href="./whole.css" />
</head>
<?php
include "db.php";
$idx= $_GET['idx']; 
$temp = "select * from challenge_join where idx='{$idx}'";
$sql = mq($temp);
$joincount= mysqli_num_rows($sql);
?>
<div id="joinmembers">
    총 : <?php 
    echo $joincount."명" ;
    while($row=$sql->fetch_array()){
        $user = $row['user']; 
        
        // 해당 이메일의 닉네임 찾기
        $temp2 = mq("select * from members where email='{$user}'");
        $nickname = $temp2->fetch_array();
        $nickname = $nickname['nickname'];
        ?>
        <!-- div를 클릭하면, 해당 이용자의 피드를 볼 수 있도록 -->
        <div style="cursor:pointer" onclick="window.open('joinerfeed.php?joiner=<?php echo $nickname ?>')">
            <span>
                 <?php echo $nickname; ?>
                 <input type="text" value="<?php echo $user?>">
            </span>  
        </div>
    <?php   } ?>
    

</div>