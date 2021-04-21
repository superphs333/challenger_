<!-- 댓글한개 -->
<?php
    // 닉네임(이메일을 통해서), 날짜, 댓글번호, 댓글내용
    // $nick, $date, $replyidx, $content, $email
    
?>
<table class="replylist_tb">
    <tr class="replylist_tr">
        <td class="replylist_td_left">
            <?php echo $nick."님"; ?>&nbsp;&nbsp;&nbsp;
            <?php echo $date ?>                
        </td>
        <td class="replylist_td_right">
            <!-- 댓글번호 정보 -->
            <input type="hidden" class="reply_num"
            value="<?php echo $replyidx; ?>">
            <a class="update">수정</a> 
            <a class="delete">삭제</a>
            <!-- 댓글 작성자(email) -->
            <input type="hidden" class="reply_email"
            value="<?php echo $email;  ?>">
            <!-- 댓글 내용 -->
            <input type="hidden" class="reply_email"
            value="<?php echo $content;  ?>">
        </td>
    </tr>
    <tr class="replylist_tr">
        <td class="replylist_contenttd" colspan="2">
            <?php echo $content; ?>            
        </td>                        
    </tr>    
</table>