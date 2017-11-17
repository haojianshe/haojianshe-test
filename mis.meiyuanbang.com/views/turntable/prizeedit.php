<?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;
?>
<link rel="stylesheet" type="text/css" href='/static/css/edit.css'>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<div class="normaltable">
    <?php $form = ActiveForm::begin(['id' => 'cmsform']); ?>   
    <table style='width:100%;'>
        <tbody>
            <?php if (isset($models[0]['gameid'])) { ?>
            <input type ="hidden" name='isedit' value='1' />
            <input type ="hidden" name="gameid" value="<?= $models[0]['gameid'] ?>" />
        <?php } ?>
        <tr>
            <td style="width: 80px">活动标题<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="title" style="width:70%" type="text" id="titleid" value="<?php echo isset($models[0]['title']) ? $models[0]['title'] : "" ?>"
                       datatype="*1-30" nullmsg="请输入活动标题，最多30个字！" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <!--选择奖品一 -->
        <tr>
            <td style="width: 80px">选择奖品一</td>
            <td>
                <div>
                    <span class="normalbtn_l">
                        <a id="rewardListOne" onclick="selectPrize('imgthumb', 'prizesidOne', $('#prizesidOne').val())" class="rewardListOne" href="#">选择</a>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <td>奖品图片<span class='need'>*</span></td>
            <td class="prize1">
                <img id='imgthumb' src="<?php echo isset($models[0]['img']) ? $models[0]['img'] : "" ?>" style='padding-left:15px;height:100px;' />
                <input type ="hidden" name="rewardone[0][prizesid]" value="<?php echo isset($models[0]['prizesid']) ? $models[0]['prizesid'] : "" ?>"  id="prizesidOne"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="border:none">
                    <tr>
                        <td>概率开始区间</td>
                        <td> <input size="10" id="probabilityStartOne"
                                    type="text" name="rewardone[0][probability_start]" value="<?php echo isset($models[0]['probability_start']) ? $models[0]['probability_start'] : "" ?>"
                                    datatype="/^-?[0-9]\d*$/" nullmsg="不能为空" errormsg="必须为数字" sucmsg="&nbsp;"
                                    /></td>
                        <td>概率结束区间</td>
                        <td> <input size="10" id="probabilityEndOne" type="text" name="rewardone[0][probability_end]"
                                    value="<?php echo isset($models[0]['probability_end']) ? $models[0]['probability_end'] : "" ?>"
                                    datatype="/^-?[0-9]\d*$/" nullmsg="不能为空" errormsg="必须为数字" sucmsg="&nbsp;"
                                    /></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品数量<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="rewardone[0][num]" style="width:70%" type="text" id="numberOne"
                       value="<?php echo isset($models[0]['num']) ? $models[0]['num'] : "" ?>"
                       datatype="/^-?[0-9]\d*$/" nullmsg="不能为空" errormsg="必须为数字" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">排序字段<span class='need'>*</span></td>
            <td>
                <input type="text"  class="inputclass1" name="rewardone[0][sort]" value="<?php echo isset($models[0]['sort']) ? $models[0]['sort'] : "" ?>" accept=""
                       datatype="/^-?[1-9]\d*$/" nullmsg="不能为空" errormsg="必须为数字" sucmsg="&nbsp;"
                       />
            </td>
        </tr>
        <!--选择奖品二 -->

        <tr>
            <td style="width: 80px">选择奖品二</td>
            <td>
                <div>
                    <span class="normalbtn_l"><a id="rewardListTwo" onclick="selectPrize('imgthumbTwo', 'prizesidTwo', $('#prizesidTwo').val())"  href="#">选择</a></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>奖品图片<span class='need'>*</span></td>
            <td>
                <img id='imgthumbTwo' src="<?php echo isset($models[1]['img']) ? $models[1]['img'] : "" ?>" style='padding-left:15px;height:100px;' />
                <input type ="hidden" name="rewardone[1][prizesid]" value="<?php echo isset($models[1]['prizesid']) ? $models[1]['prizesid'] : "" ?>"  id="prizesidTwo"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="border:none">
                    <tr>
                        <td>概率开始区间</td>
                        <td> <input size="10" id="probabilityStartTwo" type="text" name="rewardone[1][probability_start]" value="<?php echo isset($models[1]['probability_start']) ? $models[1]['probability_start'] : "" ?>"/></td>
                        <td>概率结束区间</td>
                        <td> <input size="10" id="probabilityEndTwo" type="text" name="rewardone[1][probability_end]" value="<?php echo isset($models[1]['probability_end']) ? $models[1]['probability_end'] : "" ?>"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品数量<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="rewardone[1][num]" style="width:70%" type="text" id="rewardTwo" value="<?php echo isset($models[1]['num']) ? $models[1]['num'] : "" ?>"
                       datatype="*1-30" nullmsg="请输入活动数量" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">排序字段<span class='need'>*</span></td>
            <td>
                <input type="text"  class="inputclass1" name="rewardone[1][sort]" value="<?php echo isset($models[1]['sort']) ? $models[1]['sort'] : "" ?>"/>
            </td>
        </tr>
        <!--选择奖品三 -->
        <tr>
            <td style="width: 80px">选择奖品三</td>
            <td>
                <div>
                    <span class="normalbtn_l"><a id="rewardListThree" class="rewardListOne" onclick="selectPrize('imgthumbThree', 'prizesidThree', $('#prizesidThree').val())" href="#">选择</a></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>奖品图片<span class='need'>*</span></td>
            <td>
                <img id='imgthumbThree' src="<?php echo isset($models[2]['img']) ? $models[2]['img'] : "" ?>" style='padding-left:15px;height:100px;' />
                <input type ="hidden" name="rewardone[2][prizesid]" value="<?php echo isset($models[2]['prizesid']) ? $models[2]['prizesid'] : "" ?>"  id="prizesidThree"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="border:none">
                    <tr>
                        <td>概率开始区间</td>
                        <td> <input size="10" id="probabilityStartThree" type="text" name="rewardone[2][probability_start]" value="<?php echo isset($models[2]['probability_start']) ? $models[2]['probability_start'] : "" ?>"/></td>
                        <td>概率结束区间</td>
                        <td> <input size="10" id="probabilityEndThree" type="text" name="rewardone[2][probability_end]" value="<?php echo isset($models[2]['probability_end']) ? $models[2]['probability_end'] : "" ?>"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品数量<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="rewardone[2][num]" style="width:70%" type="text" value="<?php echo isset($models[2]['num']) ? $models[2]['num'] : "" ?>"
                       datatype="*1-30" nullmsg="请输入活动数量" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">排序字段<span class='need'>*</span></td>
            <td>
                <input type="text"  class="inputclass1" name="rewardone[2][sort]" value="<?php echo isset($models[2]['sort']) ? $models[2]['sort'] : "" ?>"/>
            </td>
        </tr>
        <!--选择奖品四-->

        <tr>
            <td style="width: 80px">选择奖品四</td>
            <td>
                <div>
                    <span class="normalbtn_l"><a id="rewardList"  class="rewardListFour" onclick="selectPrize('imgthumbFour', 'prizesidFour', $('#prizesidFour').val())"  href="#">选择</a></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>奖品图片<span class='need'>*</span></td>
            <td>
                <img id='imgthumbFour' src="<?php echo isset($models[3]['img']) ? $models[3]['img'] : "" ?>" style='padding-left:15px;height:100px;' />
                <input type ="hidden" name="rewardone[3][prizesid]" value="<?php echo isset($models[3]['prizesid']) ? $models[3]['prizesid'] : "" ?>"  id="prizesidFour"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="border:none">
                    <tr>
                        <td>概率开始区间</td>
                        <td> <input size="10" id="probabilityStartFour" type="text" name="rewardone[3][probability_start]" value="<?php echo isset($models[3]['probability_start']) ? $models[3]['probability_start'] : "" ?>"/></td>
                        <td>概率结束区间</td>
                        <td> <input size="10" id="probabilityEndFour" type="text" name="rewardone[3][probability_end]" value="<?php echo isset($models[3]['probability_end']) ? $models[3]['probability_end'] : "" ?>"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品数量<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="rewardone[3][num]" style="width:70%" type="text" value="<?php echo isset($models[3]['num']) ? $models[3]['num'] : "" ?>"
                       datatype="*1-30" nullmsg="请输入活动数量" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">排序字段<span class='need'>*</span></td>
            <td>
                <input type="text"  class="inputclass1" name="rewardone[3][sort]" value="<?php echo isset($models[3]['sort']) ? $models[3]['sort'] : "" ?>"/>
            </td>
        </tr>
        <!--选择奖品五-->

        <tr>
            <td style="width: 80px">选择奖品五</td>
            <td>
                <div>
                    <span class="normalbtn_l"><a id="rewardList" class="rewardListOne" onclick="selectPrize('imgthumbFive', 'prizesidFive', $('#prizesidFive').val())" href="#">选择</a></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>奖品图片<span class='need'>*</span></td>
            <td>
                <img id='imgthumbFive' src="<?php echo isset($models[4]['img']) ? $models[4]['img'] : "" ?>" style='padding-left:15px;height:100px;' />
                <input type ="hidden" name="rewardone[4][prizesid]"  value="<?php echo isset($models[4]['prizesid']) ? $models[4]['prizesid'] : "" ?>"  id="prizesidFive"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="border:none">
                    <tr>
                        <td>概率开始区间</td>
                        <td> <input size="10" id="probabilityStartFive" type="text" name="rewardone[4][probability_start]" value="<?php echo isset($models[4]['probability_start']) ? $models[4]['probability_start'] : "" ?>"/></td>
                        <td>概率结束区间</td>
                        <td> <input size="10" id="probabilityEndFive" type="text" name="rewardone[4][probability_end]" value="<?php echo isset($models[4]['probability_end']) ? $models[4]['probability_end'] : "" ?>"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品数量<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="rewardone[4][num]" style="width:70%" type="text" value="<?php echo isset($models[4]['num']) ? $models[4]['num'] : "" ?>"
                       datatype="*1-30" nullmsg="请输入活动数量" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">排序字段<span class='need'>*</span></td>
            <td>
                <input type="text"  class="inputclass1" name="rewardone[4][sort]" value="<?php echo isset($models[4]['sort']) ? $models[4]['sort'] : "" ?>"/>
            </td>
        </tr>
        <!--选择奖品六 -->

        <tr>
            <td style="width: 80px">选择奖品六</td>
            <td>
                <div>
                    <span class="normalbtn_l"><a id="rewardList" class="rewardListOne" onclick="selectPrize('imgthumbSix', 'prizesidSix', $('#prizesidSix').val())"  href="#">选择</a></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>奖品图片<span class='need'>*</span></td>
            <td>
                <img id='imgthumbSix' src="<?php echo isset($models[5]['img']) ? $models[5]['img'] : "" ?>" style='padding-left:15px;height:100px;' />
                <input type ="hidden" name="rewardone[5][prizesid]" value="<?php echo isset($models[5]['prizesid']) ? $models[5]['prizesid'] : "" ?>"  id="prizesidSix"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="border:none">
                    <tr>
                        <td>概率开始区间</td>
                        <td> <input size="10" id="probabilityStartSix" type="text" name="rewardone[5][probability_start]" value="<?php echo isset($models[5]['probability_start']) ? $models[5]['probability_start'] : "" ?>"/></td>
                        <td>概率结束区间</td>
                        <td> <input size="10" id="probabilityEndSix" type="text" name="rewardone[5][probability_end]" value="<?php echo isset($models[5]['probability_end']) ? $models[5]['probability_end'] : "" ?>"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品数量<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="rewardone[5][num]" style="width:70%" type="text" value="<?php echo isset($models[5]['num']) ? $models[5]['num'] : "" ?>"
                       datatype="*1-30" nullmsg="请输入活动数量" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">排序字段<span class='need'>*</span></td>
            <td>
                <input type="text"  class="inputclass1" name="rewardone[5][sort]" value="<?php echo isset($models[5]['sort']) ? $models[5]['sort'] : "" ?>"/>
            </td>
        </tr>
        <!--选择奖品七-->
        <tr>
            <td style="width: 80px">选择奖品七</td>
            <td>
                <div>
                    <span class="normalbtn_l"><a id="rewardList" class="rewardListOne" onclick="selectPrize('imgthumbSeven', 'prizesidSeven', $('#prizesidSeven').val())"   href="#">选择</a></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>奖品图片<span class='need'>*</span></td>
            <td>
                <img id='imgthumbSeven' src="<?php echo isset($models[6]['img']) ? $models[6]['img'] : "" ?>" style='padding-left:15px;height:100px;' />
                <input type ="hidden"  name="rewardone[6][prizesid]"  value="<?php echo isset($models[6]['prizesid']) ? $models[6]['prizesid'] : "" ?>"  id="prizesidSeven"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="border:none">
                    <tr>
                        <td>概率开始区间</td>
                        <td> <input size="10" id="probabilityStartServen" type="text" name="rewardone[6][probability_start]" value="<?php echo isset($models[6]['probability_start']) ? $models[6]['probability_start'] : "" ?>"/></td>
                        <td>概率结束区间</td>
                        <td> <input size="10" id="probabilityEndServen" type="text" name="rewardone[6][probability_end]" value="<?php echo isset($models[6]['probability_end']) ? $models[6]['probability_end'] : "" ?>"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品数量<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="rewardone[6][num]" style="width:70%" type="text" value="<?php echo isset($models[6]['num']) ? $models[6]['num'] : "" ?>"
                       datatype="*1-30" nullmsg="请输入活动数量" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">排序字段<span class='need'>*</span></td>
            <td>
                <input type="text"  class="inputclass1" name="rewardone[6][sort]" value="<?php echo isset($models[6]['sort']) ? $models[6]['sort'] : "" ?>"/>
            </td>
        </tr>
        <!--选择奖品八 -->
        <tr>
            <td style="width: 80px">选择奖品八</td>
            <td>
                <div>
                    <span class="normalbtn_l"><a id="rewardList"  class="rewardListOne"  onclick="selectPrize('imgthumbEight', 'prizesidEight', $('#prizesidEight').val())" href="#">选择</a></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>奖品图片<span class='need'>*</span></td>
            <td>
                <img id='imgthumbEight' src="<?php echo isset($models[7]['img']) ? $models[7]['img'] : "" ?>" style='padding-left:15px;height:100px;' />
                <input type ="hidden"   name="rewardone[7][prizesid]"  value="<?php echo isset($models[7]['prizesid']) ? $models[7]['prizesid'] : "" ?>"  id="prizesidEight"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="border:none">
                    <tr>
                        <td>概率开始区间</td>
                        <td> <input size="10" id="probabilityStartEight" type="text" name="rewardone[7][probability_start]" value="<?php echo isset($models[7]['probability_start']) ? $models[7]['probability_start'] : "" ?>"/></td>
                        <td>概率结束区间</td>
                        <td> <input size="10" id="probabilityEndEight" type="text" name="rewardone[7][probability_end]" value="<?php echo isset($models[7]['probability_end']) ? $models[7]['probability_end'] : "" ?>"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">奖品数量<span class='need'>*</span></td>
            <td>
                <input class="inputclass1" name="rewardone[7][num]" style="width:70%" type="text" value="<?php echo isset($models[7]['num']) ? $models[7]['num'] : "" ?>"
                       datatype="*1-30" nullmsg="请输入活动数量" sucmsg="&nbsp;"/>
            </td>
        </tr>
        <tr>
            <td style="width: 80px">排序字段<span class='need'>*</span></td>
            <td>
                <input type="text"  class="inputclass1" name="rewardone[7][sort]" value="<?php echo isset($models[7]['sort']) ? $models[7]['sort'] : "" ?>"/>
            </td>
        </tr>
        <tr>
            <?php
            if (@$models['viewId'] == '' || $models['viewId'] == 1) {
                ?>
                <td>
                    <span class="normalbtn_l"><a id="asave" href="#">保存</a></span>
                </td>
            <?php
            } else {
                echo '<td></td>';
            }
            ?>
            <td>
                <span class="normalbtn_l"><a id="aclose" href="#">关闭</a></span>
            </td>
        </tr>
        </tbody>
    </table>
    <?php ActiveForm::end(); ?> 
</div>

<script>
    //父窗口句柄
    var index = parent.layer.getFrameIndex(window.name);
    function selectPrize(v, i, id) {
        var content = '/turntable/rewardlist';
        var title = '选择商品';
        var content = content + '?v=' + v + '&i=' + i + '&id=' + id + '&url=' + encodeURI($('#thumb').val());
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
           area: ['80%', '85%'],
            content: content
        });
    }
    //显示富文本框内容
      var ue = UE.getEditor('editor',{
                                    initialFrameWidth:750,
                                    initialFrameHeight:700,
                                });
    ue.ready(function () {
        ue.setContent('<?= @$model->content ?>');
    });
    //点击缩略图事件
    $("a[name=athumb]").click(function () {
        var content = '/activity/thumbupload';
        var title = '编辑缩略图';
        content = content + '?url=' + encodeURI($('#thumb').val());
        layer.open({
            type: 2,
            title: title,
            maxmin: false,
            shadeClose: false, //点击遮罩关闭层
            area: ['600px', '400px'],
            content: content
        });
        return false;
    });
    //保存按钮
    $("#asave").click(function () {
        //奖品一
        var prizesidOne = $('#prizesidOne').val();
        if (prizesidOne == '') {
            layer.msg('奖品一没有选择图片', {icon: 2});
            return false;
        }
        var StartVal = $('#probabilityStartOne').val();
        var EndVal = $('#probabilityEndOne').val();
        var num = StartVal - EndVal;
        if (num > 0) {
            layer.msg('活动一概率开始值大于概率结束值', {icon: 2});
            return false;
        }
        //奖品二
        var prizesidTwo = $('#prizesidTwo').val();
        if (prizesidTwo == '') {
            layer.msg('奖品二没有选择图片', {icon: 2});
            return false;
        }
        var StartTwoVal = $('#probabilityStartTwo').val();
        var EndTwoVal = $('#probabilityEndTwo').val();
        var difference = StartTwoVal - EndVal;
        var numTwo = StartTwoVal - EndTwoVal;
        if (difference <= 0) {
            layer.msg('活动二概率开始值大于或等于活动一概率结束值', {icon: 2});
            return false;
        }
        if (numTwo >= 0) {
            layer.msg('活动二概率开始值大于或等于概率结束值', {icon: 2});
            return false;
        }
        //奖品三
        var prizesidThree = $('#prizesidThree').val();
        if (prizesidThree == '') {
            layer.msg('奖品三没有选择图片', {icon: 2});
            return false;
        }
        var StartThree = $('#probabilityStartThree').val();
        var EndThree = $('#probabilityEndThree').val();
        var differenceThree = StartThree - EndTwoVal;
        var numThree = StartThree - EndThree;
        if (differenceThree <= 0) {
            layer.msg('活动三概率开始值大于或等于活动二概率结束值', {icon: 2});
            return false;
        }
        if (numThree >= 0) {
            layer.msg('活动三概率开始值大于或等于概率结束值', {icon: 2});
            return false;
        }
        //奖品四
        var prizesidFour = $('#prizesidFour').val();
        if (prizesidFour == '') {
            layer.msg('奖品四没有选择图片', {icon: 2});
            return false;
        }
        var StartFour = $('#probabilityStartFour').val();
        var EndFour = $('#probabilityEndFour').val();
        var differenceFour = StartFour - EndThree;
        var numFour = StartFour - EndFour;
        if (differenceFour <= 0) {
            layer.msg('活动四概率开始值大于或等于活动三概率结束值', {icon: 2});
            return false;
        }
        if (numFour >= 0) {
            layer.msg('活动四概率开始值大于或等于概率结束值', {icon: 2});
            return false;
        }
        //奖品五
        var prizesidFive = $('#prizesidFive').val();
        if (prizesidFive == '') {
            layer.msg('奖品五没有选择图片', {icon: 2});
            return false;
        }
        var StartFive = $('#probabilityStartFive').val();
        var EndFive = $('#probabilityEndFive').val();
        var differenceFive = StartFive - EndFour;
        var numFive = StartFive - EndFive;
        if (differenceFive <= 0) {
            layer.msg('活动五概率开始值大于或等于活动四概率结束值', {icon: 2});
            return false;
        }
        if (numFive >= 0) {
            layer.msg('活动五概率开始值大于或等于概率结束值', {icon: 2});
            return false;
        }
        //奖品六
        var prizesidSix = $('#prizesidSix').val();
        if (prizesidSix == '') {
            layer.msg('奖品六没有选择图片', {icon: 2});
            return false;
        }
        var StartSix = $('#probabilityStartSix').val();
        var EndSix = $('#probabilityEndSix').val();
        var differenceSix = StartSix - EndFive;
        var numSix = StartSix - EndSix;
        if (differenceSix <= 0) {
            layer.msg('活动六概率开始值大于或等于活动五概率结束值', {icon: 2});
            return false;
        }
        if (numSix >= 0) {
            layer.msg('活动六概率开始值大于或等于概率结束值', {icon: 2});
            return false;
        }
        //奖品七
        var prizesidSeven = $('#prizesidSeven').val();
        if (prizesidSeven == '') {
            layer.msg('奖品七没有选择图片', {icon: 2});
            return false;
        }
        var StartServen = $('#probabilityStartServen').val();
        var EndServen = $('#probabilityEndServen').val();
        var differenceServen = StartServen - EndSix;
        var numServen = StartServen - EndServen;
        if (differenceServen <= 0) {
            layer.msg('奖品七概率开始值大于或等于奖品六概率结束值', {icon: 2});
            return false;
        }
        if (numServen >= 0) {
            layer.msg('奖品七概率开始值大于或等于概率结束值', {icon: 2});
            return false;
        }
        //奖品七
        var prizesidEight = $('#prizesidEight').val();
        if (prizesidEight == '') {
            layer.msg('奖品八没有选择图片', {icon: 2});
            return false;
        }
        var StartEight = $('#probabilityStartEight').val();
        var EndEight = $('#probabilityEndEight').val();
        var differenceEight = StartEight - EndServen;
        var numEight = StartEight - EndEight;
        if (differenceEight <= 0) {
            layer.msg('奖品八概率开始值大于或等于奖品七概率结束值', {icon: 2});
            return false;
        }
        if (numEight >= 0) {
            layer.msg('奖品八概率开始值大于或等于概率结束值', {icon: 2});
            return false;
        }
        $("form").submit();
        return false;
    });
    //关闭按钮,刷新父窗口
        $('#aclose').click(function(){
        	parent.layer.close(index);
        });
        //保存成功后自动关闭
        <? if(isset($msg) && $msg<>''){ ?>
        	<?if(isset($isclose) && $isclose){ ?>
        		layer.msg('<?= $msg ?>', {icon: 1});
	        	setTimeout(function (){
	        		//parent.location.reload();
					window.parent.location.reload();
	           }, 1000);
	      	<? } else{ ?>
	      		layer.msg('<?= $msg ?>', {icon: 2});
	      	<? } ?>
        <? } ?>
		//表单验证
        $("#cmsform").Validform({
    		tiptype:3,
    	});
</script>