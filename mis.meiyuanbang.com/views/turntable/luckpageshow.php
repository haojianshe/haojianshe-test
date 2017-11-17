
<style>
    body,div,ol,ul,h1,h2,h3,h4,h5,h6,p,th,td,dl,dd,form,iframe,input,textarea,select,label,article,aside,footer,header,menu,nav,section,time,audio,video {  margin:0;  padding:0}
article,aside,footer,header,hgroup,nav,section,audio,canvas,video,img {  display:block}
textarea {resize:none}
iframe,img {border:0}
ul,ol,menu,li {list-style:none; margin:0; padding: 0;}
menu,li,p { margin:0; padding: 0;}
input,select,textarea {outline:0; }
a {text-decoration:none; color: #333}
a:focus,input:focus{color: #555}
a,a:hover {text-decoration:none}
a:hover{color:#333}
body{color: #333; background: #e7e7e7;}

.clear:after,.deail_box h2:after,.ranking_list li:after,.marquee li:after{content:'\0020';display:block;height:0;clear:both;}
.clear,.comment_ul li,.deail_box h2,.ranking_list li,.marquee li{*zoom: 1;}
body{
 font-family:"\5FAE\8F6F\96C5\9ED1","PingHei","PingFang SC","Helvetica Neue","Helvetica","STHeitiSC-Light","Arial",sans-serif;
}

.luck_region{
	background: url(/static/mactivity/images/luck_bg_big.png) no-repeat center;
	background-size: 100%;
	width: 490px;
	height: 750px;
	margin:0 auto;
}
.luck_region ul{
	padding: 8px;
}
.luck_region li{
	width: 150px;
	height: 150px;
	float: left;
	border:1px solid #000;
	border-radius: 10px;
	margin-left: 5px;
	margin-top: 5px;
	background: #7f7f7f;

}
.luck_region li img{
	opacity: 0.5;
}
.luck_region li.active img{
	opacity: 1;

}
.luck_region li img{
	width: 150px;
	height: 150px;
	border-radius: 10px
}
.luck_region li a{
	width: 360px;
	height: 360px;
	display: block;
	background: url(/static/mactivity/images/luck_s_drave.png) no-repeat center;
	background-size: 100%;

}


    
</style>
        <div class="luck_region" id="lottery">
            <ul>
                <?php
                # print_r($prizelist);
                ?>
                <li class="lottery-unit lottery-unit-0" id="id_<?php echo $prizelist[0]['prizesid'] ?>" value="<?php echo $prizelist[0]['prizesid'] ?>">
                    <img src="<?php echo $prizelist[0]['img'] ?>">
                </li>
                <li class="lottery-unit lottery-unit-1" id="id_<?php echo $prizelist[1]['prizesid'] ?>" value="<?php echo $prizelist[1]['prizesid'] ?>">
                    <img src="<?php echo $prizelist[1]['img'] ?>">
                </li>
                <li class="lottery-unit lottery-unit-2" id="id_<?php echo $prizelist[2]['prizesid'] ?>" value="<?php echo $prizelist[2]['prizesid'] ?>">
                    <img src="<?php echo $prizelist[2]['img'] ?>">
                </li>
                <li class="lottery-unit lottery-unit-7" id="id_<?php echo $prizelist[7]['prizesid'] ?>" value="<?php echo $prizelist[7]['prizesid'] ?>">
                    <img src="<?php echo $prizelist[7]['img'] ?>">
                </li>
                <li>
                    <a href="#"></a>
                </li>
                <li class="lottery-unit lottery-unit-3" id="id_<?php echo $prizelist[3]['prizesid'] ?>" value="<?php echo $prizelist[3]['prizesid'] ?>">
                    <img src="<?php echo $prizelist[3]['img'] ?>">
                </li>
                <li class="lottery-unit lottery-unit-6" id="id_<?php echo $prizelist[6]['prizesid'] ?>" value="<?php echo $prizelist[6]['prizesid'] ?>">
                    <img src="<?php echo $prizelist[6]['img'] ?>">
                </li>
                <li class="lottery-unit lottery-unit-5" id="id_<?php echo $prizelist[5]['prizesid'] ?>" value="<?php echo $prizelist[5]['prizesid'] ?>">
                    <img src="<?php echo $prizelist[5]['img'] ?>">
                </li>
                <li class="lottery-unit lottery-unit-4" id="id_<?php echo $prizelist[4]['prizesid'] ?>" value="<?php echo $prizelist[4]['prizesid'] ?>">
                    <img src="<?php echo $prizelist[4]['img'] ?>">
                </li>

            </ul>
        </div>




