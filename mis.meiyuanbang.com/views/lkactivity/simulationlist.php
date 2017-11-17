<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- 时间选择框样式 -->
<link rel="stylesheet" type="text/css" href="/static/js/calendar/jscal2.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/border-radius.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/win2k.css"/>
<link rel="stylesheet" type="text/css" href="/static/js/calendar/calendar-blue.css"/>
<!-- 时间选择框js -->
<script type="text/javascript" src="/static/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/static/js/calendar/lang/en.js"></script>

<div class="normaltable">
    <table style='width:100%;'>
        <thead>
        <tr> 
        <th>城市</th> 
        <th>查看</th> 
        </tr> 
        </thead>
        <tbody>
            <?php
            if(!empty($data)){
                foreach($data as $key=>$val){
            ?>
            <tr>
                <td><?php echo $val.'模拟联考'?></td>
                <td><a href="/lkactivity/simulationdetail?cityid=<?php echo $key?>">查看</a></td>
            </tr>
            
            <?php
                }
            }
            ?>

        </tbody>
    </table> 
   
</div>