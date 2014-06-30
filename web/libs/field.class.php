<?php

/*
 * Copyright  : [后盾人才招聘系统] (C)2011-2012 后盾网 ，Inc. 
 * Link       : http://www.houdunwang.com
 * Encoding   : UTF-8
 * Author     : 张博
 * Email      : zhangbo1248@gmail.com
 * Created    : 2012-8-24
 * Describe   : 生成、更新模型视图。更新模型结构、缓存模型字段
 */

class field extends Control {
    private $model_info;
    private $model;
    private $model_field;
    private $html_struct=array();
    private $model_id;
    /**
     * @param int $model模型表 ID
     */
    public function __construct($model=null)
    {
        $this->model_id=$model;
        $this->model=M('model');
        $this->model_info=$this->model->find($model);
        if(!$this->model_info){
            $this->error('不存在该模型。');
        }
        $this->model_field=include PATH_ROOT.'/caches/model/field/m_'.$this->model_info['name'].'.php';
    }

    /**
     * 生成每个字段的机构。
     */
    public function build_field()
    {
        foreach ($this->model_field as $field) {
            if($field['state']){
                $this->html_struct[$field['field_name']]=array(
                    'title'=>$field['title'],
                    'field_tips'=>$field['field_tips'],
                    'add_html'=>'',
                    'edit_html'=>''
                );
                $this->build_struct($field);
            }
        }
        $data='<?php return '.var_export($this->html_struct,true).';';
        file_put_contents(PATH_ROOT.'/caches/model/struct/'.$this->model_info['name'].'.php', $data);
        $this->generate_html();
    }

    public function build_struct($data)
    {
        $data['rule']=json_decode($data['rule']);
        $tags = new MyTags;
        $rule = '';
        $validate = '';
        $width = empty($data['width']) ? '' : ' width:' . $data['width'] . 'px;';
        $height = empty($data['height']) ? '' : ' height:' . $data['height'] . 'px;';
        $style = '';
        if ($width . $height != '') {
            $style = ' style="' . $width . $height . '" ';
        }
        if (!empty($data['rule'])) {
            $validate = ' validate={';
            foreach ($data['rule'] as $rule_key => $rule_value) {
                $rule.='"' . $rule_key . '"' . ':' . $rule_value . ',';
            }
//组合错误消息
            if (trim($data['error_tips']) != '') {
                $rule.='"messages":"' . $data['error_tips'] . '"';
            }
            $validate .= trim($rule, ',') . '} ';
        }
        if (substr($data['field_type'], 0, 6) == 'switch' && $data['setting']['type'] == 'option') {
            $this->html_struct[$data['field_name']]['add_html'] = '<select name="' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $validate . '><option value="">请选择</option>';
            $this->html_struct[$data['field_name']]['edit_html'] = '<select name="' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $validate . '><option value="">请选择</option>';
        }
        if (substr($data['field_type'], 0, 5) == 'input') {
            $this->html_struct[$data['field_name']]['add_html'] = '<input type="text" name="' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $data['html_attr'] . $validate . ' value="' . $data['default_val'] . '" />';
            $this->html_struct[$data['field_name']]['edit_html'] = "<input type=\"text\" name=\"" . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $data['html_attr'] . $validate . $style . " value=\"<?php echo $" . $data['field_name'] . ";?>\" />";
        }
        if ($data['field_type'] == 'switch') {
            foreach ($data['data'] as $radio_v => $radio_n) {
                if ($radio_v == $data['default_val']) {
                    $checked = 'checked="checked"';
                    $selected = 'selected="selected"';
                } else {
                    $checked = '';
                    $selected = $checked;
                }
                $edit_checked = '<?php if(in_array("'.$radio_v.'",explode("#",$' . $data['field_name'] . '))):?>checked<?php endif;?>';
                $edit_selected = '<?php if($' . $data['field_name'] . '=="' . $radio_v . '"):?>selected<?php endif;?>';
                if ($data['setting']['type'] == 'radio') {
                    $this->html_struct[$data['field_name']]['add_html'] .= '<label><input type="' . $data['setting']['type'] . "\" name=\"{$data['field_name']}\" {$data['js_event']} value=\"{$radio_v}\"{$validate} $checked />{$radio_n}</label>";

                    $this->html_struct[$data['field_name']]['edit_html'] .= '<label><input type="' . $data['setting']['type'] . "\" name=\"{$data['field_name']}\" {$data['js_event']} value=\"{$radio_v}\"{$validate} $edit_checked />{$radio_n}</label>";
                }else if($data['setting']['type'] == 'checkbox'){
                    $this->html_struct[$data['field_name']]['add_html'] .= '<label><input type="' . $data['setting']['type'] . "\" name=\"{$data['field_name']}[]\" {$data['js_event']} value=\"{$radio_v}\"{$validate} $checked />{$radio_n}</label>";
                    $this->html_struct[$data['field_name']]['edit_html'] .= '<label><input type="' . $data['setting']['type'] . "\" name=\"{$data['field_name']}[]\" {$data['js_event']} value=\"{$radio_v}\"{$validate} $edit_checked />{$radio_n}</label>";
                }else {//下拉列表
                    $this->html_struct[$data['field_name']]['add_html'] .= '<option value="' . $radio_v . '" ' . $selected . '>' . $radio_n . '</option>';
                    $this->html_struct[$data['field_name']]['edit_html'] .= '<option value="' . $radio_v . '" ' . $edit_selected . '>' . $radio_n . '</option>';
                }
            }
        }
        if (substr($data['field_type'], 0, 6) == 'switch' && $data['setting']['type'] == 'option') {
            $this->html_struct[$data['field_name']]['add_html'] .= '</select>';
            $this->html_struct[$data['field_name']]['edit_html'] .= '</select>';
        }
        if ($data['field_type'] == 'editor') {//编辑器
            $editor_attr = array(
                'name' => $data['field_name'],
                'id' => $data['field_name'],
                'content' => $data['default_val'],
                'width' => $data['width'],
                'height' => $data['height'],
                'style' => $data['editor_style'],
            );
            if(isset($data['rule']['maxlength'])){
                $editor_attr['max'] = $data['rule']['maxlength']; //最大输入字数
            }
            $this->html_struct[$data['field_name']]['add_html'] = $tags->_editor($editor_attr, '');
            $editor_attr['content'] = '$' . $data['field_name'];
            $this->html_struct[$data['field_name']]['edit_html'] = $tags->_editor($editor_attr, '');
        }
        if ($data['field_type'] == 'textarea') {//文本域
            $this->html_struct[$data['field_name']]['add_html'] = '<textarea name = "' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $data['html_attr'] . $validate . $style . ' >' . $data['default_val'] . '</textarea>';
            $this->html_struct[$data['field_name']]['edit_html'] = '<textarea name = "' . $data['field_name'] . '" ' . $data['js_event'] . ' ' . $data['html_attr'] . $validate . $style . ' ><?php echo $' . $data['field_name'] . ';?></textarea>';
        }
        if ($data['field_type'] == 'linkage') {//联动数据
            $attr = array();
            $data['attached']=json_decode($data['attached']);
            if (!empty($data['attached'])) {
                $attr['field'] = $data['field_name'] . '#';
                $attr['edit_field'] = '$' . $data['field_name'] . '#$';
                $attr['field'].=implode('#', $data['attached']);
                $attr['edit_field'].=implode('#$', $data['attached']);
            } else {
                $attr['field'] = $data['field_name'];
                $attr['edit_field'] = '$' . $data['field_name'];
            }
            $attr['edit_field'] = rtrim($attr['edit_field'], '#$');

            $attr['data'] = $data['lcgid'];
            $attr['style'] = $data['linkage_style'];
            $attr['attr'] = $data['html_attr'] . $validate;
            $attr['checkbox'] = isset($data['setting']['checkbox']) ? 'true' : 'false';
            if (!empty($data['default_val'])) {
                $attr['defaults'] = $data['default_val'];
            }
            $this->html_struct[$data['field_name']]['add_html'] = $tags->_linkage($attr, '');
            $attr['defaults'] = $attr['edit_field'];
            $this->html_struct[$data['field_name']]['edit_html'] = $tags->_linkage($attr, '');
        }
    }

    /**
     * 根据组合好的模型表单结构、生成HTML视图
     * @param int $model 模型ID
     */
    public function generate_html()
    {
        $db=M('model_field');
        $cond = array('dmid' => $this->model_id);
        $struct = include PATH_ROOT.'/caches/model/struct/'.$this->model_info['name'].'.php';
        $tpl_path = PATH_ROOT . '/caches/model/model_';
        //查找联动数据id的和风格样式
        $cond[]='lcgid > 0';
        $linkage = $db->where($cond)->count();
        $style = $db->field('linkage_style')->where('lcgid != "0" AND dmid="' . $this->model_id . '"')->group('linkage_style')->findall();
        $script='';
        if($linkage) {
            $script='<script type="text/javascript" src="__ROOT__/caches/js/linkage_data.js"></script>';
        }
        if(is_array($style)){
            foreach ($style as $value) {
                $script.='<script type="text/javascript" src="__ROOT__/public/js/linkage/linkage_style_' . $value['linkage_style'] . '.js"></script>';
            }
        }
        $fields = array();
        foreach ($struct as $k => $v) {
            $fields[$k] = $v;
            $fields[$k]['html'] = $v['add_html'];
            unset($fields[$k]['add_html']);
            unset($fields[$k]['edit_html']);
        }


        //获取前端配置文件
        $front_config=include PATH_ROOT.'/config/app.php';
        $style=$front_config['TPL_DIR'].'/'.$front_config['TPL_STYLE'];

        //更新发布模板
        $this->assign('fields', $fields);
        $field_add = $this->display($style .'/model/'. $this->model_info['issue_tpl'], NULL, 'text/html', 'utf-8', FALSE);
        file_put_contents($tpl_path . $this->model_id . '_add.html', $script . $field_add);

        $fields = array();
        foreach ($struct as $k => $v) {
            $fields[$k] = $v;
            $fields[$k]['html'] = $v['edit_html'];
            unset($fields[$k]['add_html']);
            unset($fields[$k]['edit_html']);
        }
        $this->assign('fields', $fields);
        //更新修改模板
        $field_edit= $this->display($style .'/model/'. $this->model_info['edit_tpl'], NULL, 'text/html', 'utf-8', FALSE);
        file_put_contents($tpl_path . $this->model_id . '_edit.html', $script . $field_edit);
    }
    /**
     * 过滤前台表单的一些输入。如在标题输入<script></script>登不安全代码
     * @param array $fieldValue 前台处理值
     * @param array 过滤后的数组
     */
    public function filterField($fieldValue)
    {
        if(!is_array($fieldValue)){
            $this->error('数据有误！');
        }
        foreach ($this->model_field as $field_name => $field) {
            if($field['state']){
                switch ($field['field_type']) {
                    case 'input_varchar'://单行文本框
                        $fieldValue[$field_name]=strip_tags($fieldValue[$field_name]);
                        break;
                    case 'input_char'://单行文本框
                        $fieldValue[$field_name]=strip_tags($fieldValue[$field_name]);
                        break;
                    case 'input_float':
                        $fieldValue[$field_name]=floatval($fieldValue[$field_name]);
                        break;
                    case 'input_decimal':
                        $fieldValue[$field_name]=floatval($fieldValue[$field_name]);
                        break;
                    case 'input_double':
                        $fieldValue[$field_name]=floatval($fieldValue[$field_name]);
                        break;
                    case 'linkage'://联动数据
                        if(!isset($field['setting']['checkbox'])){
                            $fieldValue[$field_name]=intval($fieldValue[$field_name]);
                        }
                        break;
                    case 'editor'://编辑器
                        $fieldValue[$field_name]=htmlspecialchars($fieldValue[$field_name]);
                        break;
                    case 'textarea'://文本域
                        $fieldValue[$field_name]=htmlspecialchars($fieldValue[$field_name]);
                        break;
                    case 'input_int'://联动数据
                        $fieldValue[$field_name]=intval($fieldValue[$field_name]);
                        break;
                    case 'switch':
                        $fieldValue[$field_name]=strip_tags($fieldValue[$field_name]);
                        break;
                    default:
                        $fieldValue[$field_name]=htmlspecialchars($fieldValue[$field_name]);
                        break;
                }
            }
        }
        return $fieldValue;
    }
}

