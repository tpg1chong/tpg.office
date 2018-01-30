<div class="pll mhl mtl">
<ul class="uiList settingsList">
    <?php foreach ($list as $key => $value) {

        $class = '';
        
        if( $this->section == $value['section'] ){
            $class .= !empty($class) ? ' ':'';
            $class .= 'openPanel';
        }
        
     ?>
    <li class="<?=$class?>">
        <div class="clearfix settingsListLink hidden_elem">

            <div class="rfloat">
                <a class="js-edit" href="<?=URL?>manage/building/<?=$this->item['id']?>/<?=$value['section']?>"><i class="icon-pencil mrs"></i><span>แก้ไข</span></a>
            </div>

            <div class="label"><?=$value['label']?></div>
        </div>
        <div class="content">
            <?php 
                if( $this->section == $value['section'] ){
                    require "sections/{$value['section']}.php";
                } 
            ?>
        </div>
    </li>
    <?php } ?>
</ul>
</div>