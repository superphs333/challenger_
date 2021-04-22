<head>
    <!-- 외부 css 불러오기 -->
    <link rel="stylesheet" type="text/css" href="./whole.css" />
</head>
<?php
include_once "db.php";

// idx값 가져오기
$idx= $_GET['idx']; 
$temp = "select * from challenge_join left join members on challenge_join.user=members.email where idx='{$idx}'";
//echo $temp;
$sql = mq($temp);
$joincount= mysqli_num_rows($sql);
?>
<div id="joinmembers">
    총 : <?php 
    echo $joincount."명" ;
    while($row=$sql->fetch_array()){
        $user = $row['user']; 
        
        // 해당 이메일의 닉네임 찾기
        $nickname = $row['nickname'];

        ?>
        <!-- div를 클릭하면, 해당 이용자의 피드를 볼 수 있도록 -->
        <div style="cursor:pointer" onclick="window.open('joinerfeed.php?joiner=<?php echo $nickname ?>')">
            <span>
                 <?php echo $nickname; ?>
                 <input type="hidden" value="<?php echo $user?>">
            </span>  
        </div>
    <?php   } ?>
    

</div>