<?php

/**
 * Copyright              [HD框架] (C)2011-2012 houdunwang ，Inc. 
 * Encoding               UTF-8
 * Version                 $Id: newEmptyPHP.php      2011-6-23 下午04:10:27
 * @author                向军
 * Link                       http://www.houdunwang.com       
 * E-mail                    houdunwang@gmail.com
 */
class page {

    public $total_row; //总条数
    public $total_page; //总页数
    public $arc_row; //每页显示数
    public $page_row; //每页显示页码数
    public $style; //界面风格
    private $self_page; //当前页
    public $url; //页面地址
    public $args; //页面传递参数
    public $start_id; //当前页开始ID
    public $end_id; //当前页末尾ID
    public $desc = array(); //文字描述

    /**
     * 构造函数
     * @param number $total       总条数
     * @param number $row         每页显示条数
     * @param number $page_row    显示页码数量
     * @param number $style       页码样式 
     * @param array $desc        描述文字
     */

    function __construct($total, $row = '', $page_row = '', $style = '', $desc = '') {
        $this->total_row = $total; //总条数
        $this->arc_row = empty($row) ? C("ARC_ROW") : $row; //每页显示条数
        $this->page_row = empty($page_row) ? C('PAGE_ROW') : $page_row; //显示页码数量 
        $this->style = empty($style) ? C('PAGE_STYLE') : $style; //页码样式 
        $this->total_page = ceil($this->total_row / $this->arc_row); //总页数
        $this->self_page = min($this->total_page, empty($_GET[C("PAGE_VAR")]) ? 1 : max(1, (int) $_GET[C("PAGE_VAR")])); //当前页
        $this->url = $this->setUrl(); //配置url地址			
        $this->start_id = ($this->self_page - 1) * $this->arc_row + 1; //当前页开始ID
        $this->end_id = min($this->self_page * $this->arc_row, $this->total_row); //当前页结束ID
        $this->desc = $this->desc($desc);
    }

    /**
     * 
     * 配置描述文字 
     * @param array $desc
     * "pre"=>"上一页"
     * "next"=>"下一页"
     * "pres"=>"前十页"
     * "nexts"=>"下十页"
     * "first"=>"首页"
     * "end"=>"尾页"
     * "unit"=>"条"
     */
    private function desc($desc) {
        $this->desc = array_change_key_case(C('PAGE_DESC'));
        if (empty($desc) || !is_array($desc))
            return $this->desc;

        function filter($v) {
            return !empty($v);
        }

        return array_merge($this->desc, array_filter($desc, "filter"));
    }

    //配置URL地址
    protected function setUrl() {
        $get = $_GET;
        unset($get["a"]);
        unset($get['c']);
        unset($get["m"]);
        unset($get[C("PAGE_VAR")]);
        $url_type = C("URL_TYPE");
        switch ($url_type) {
            case 1:
                $url = __METH__ . '/';
                foreach ($get as $k => $v) {
                    $url.=$k . '/' . $v . '/';
                }
                return rtrim($url, '/') . '/' . C("PAGE_VAR") . '/';
                break;
            case 2:
                $url = __METH__ . '&';
                foreach ($get as $k => $v) {
                    $url.=$k . "=" . $v . '&';
                }
                return $url . C("PAGE_VAR") . '=';
        }
    }

    //SQL的limit语句
    public function limit() {
        return array("limit" => max(0, ($this->self_page - 1) * $this->arc_row) . "," . $this->arc_row);
    }

    //上一页
    protected function pre() {
        if ($this->self_page > 1 && $this->self_page <= $this->total_page) {
            return "<a href='" . $this->url . ($this->self_page - 1) . "'>{$this->desc['pre']}</a>";
        }else{
            return "<span>上一页</span>";
        }
        return;
    }

    //下一页
    public function next() {
        $next = $this->desc ['next'];
        if ($this->self_page < $this->total_page) {
            return "<a href='" . $this->url . ($this->self_page + 1) . "'>{$next}</a>";
        }  else {
            return "<span>下一页</span>";
        }
        return;
    }

    //列表项
    private function pagelist() {
        //页码		
        $pagelist = '';
        $start = max(1, min($this->self_page - ceil($this->page_row / 2), $this->total_page - $this->page_row));
        $end = min($this->page_row + $start, $this->total_page);
        if ($end == 1)//只有一页不显示页码
            return '';
        for ($i = $start; $i <= $end; $i++) {
            if ($this->self_page == $i) {
                $pagelist [$i] ['url'] = "";
                $pagelist [$i] ['str'] = $i;
                continue;
            }
            $pagelist [$i] ['url'] = $this->url . $i;
            $pagelist [$i] ['str'] = $i;
        }
        return $pagelist;
    }

    //文字页码列表
    public function strlist() {
        $arr = $this->pagelist();
        $str = '';
        if (empty($arr))
            return;
        foreach ($arr as $v) {
            $str .= empty($v ['url']) ? "<strong>" . $v ['str'] . "</strong>" : "<a href={$v['url']}>{$v['str']}</a>&nbsp;";
        }
        return $str;
    }

    //图标页码列表 
    public function piclist() {
        $str = '';
        $first = $this->self_page == 1 ? "" : "<a href='{$this->url}1'><span class='current'><<</span></a>&nbsp;";
        $end = $this->self_page >= $this->total_page ? "" : "<a href='{$this->url}{$this->total_page}'><span class='current'>>></span></a>&nbsp;";
        $pre = $this->self_page <= 1 ? "" : "<a href='{$this->url}" . ($this->self_page - 1) . "'><span class='current'><</span></a>&nbsp;";
        $next = $this->self_page >= $this->total_page ? "" : "<a href='{$this->url}" . ($this->self_page + 1) . "'><span class='current'>></span></a>&nbsp;";

        return $first . $pre . $next . $end;
    }

    //选项列表 
    public function select() {
        $arr = $this->pagelist();
        if (!$arr) {
            return '';
        }
        $str = "<select name='page' class='page_select' onchange='
		javascript:
		location.href=this.options[selectedIndex].value;'>";
        foreach ($arr as $v) {
            $str .= empty($v ['url']) ? "<option value='{$this->url}{$v['str']}' selected='selected'>{$v['str']}</option>" : "<option value='{$v['url']}'>{$v['str']}</option>";
        }
        return $str . "</select>";
    }

    //输入框 
    public function input() {
        $str = "<input id='page' type='text' name='page' value='{$this->self_page}' class='pageinput' onkeydown = \"javascript:
					if(event.keyCode==13){
						location.href='{$this->url}'+this.value;
					}
				\"/>
				<button onclick = \"javascript:
					var input = document.getElementById('page');
					location.href='{$this->url}'+input.value;
				\">进入</button>
";
        return $str;
    }

    //前几页
    public function pres() {
        $num = max(1, $this->self_page - $this->page_row);
        return $this->self_page > $this->page_row ? "<a href='" . $this->url . $num . "'>前{$this->page_row}页</a>&nbsp" : "";
    }

    //后几页
    public function nexts() {
        $num = min($this->total_page, $this->self_page + $this->page_row);
        return $this->self_page + $this->page_row < $this->total_page ? "<a href='" . $this->url . $num . "'>后{$this->page_row}页</a>&nbsp" : "";
    }

    //首页
    public function first() {
        $first = $this->desc ['first'];
        return $this->self_page - $this->page_row > 1 ? "<a href='" . $this->url . "1'>{$first}</a>&nbsp;" : "";
    }

    //末页
    public function end() {
        $end = $this->desc ['end'];
        return $this->self_page < $this->total_page - $this->page_row ? "<a href='" . $this->url . $this->total_page . "'>{$end}</a>&nbsp;" : "";
    }

    //当前页记录
    public function nowpage() {
        return L("page_nowpage") . "{$this->start_id}-{$this->end_id}{$this->desc['unit']}";
    }

    //count统计 
    public function count() {
        return "<span>[" . L("page_count1") . "{$this->total_page}" . L("page_count2") . "] [{$this->total_row}" . L("page_count3") . "]</span>";
    }

    public function getAll() {
        $show = array();
        $show['count'] = $this->count();
        $show['first'] = $this->first();
        $show['pre'] = $this->pre();
        $show['pres'] = $this->pres();
        $show['strlist'] = $this->strlist();
        $show['nexts'] = $this->nexts();
        $show['next'] = $this->next();
        $show['end'] = $this->end();
        $show['nowpage'] = $this->nowpage();
        $show['select'] = $this->select();
        $show['input'] = $this->input();
        $show['piclist'] = $this->piclist();
        return $show;
    }

    //页码风格
    public function show($s = '') {
        if (empty($s)) {
            $s = $this->style;
        }
        switch ($s) {
            case 1 :
                return "{$this->count()}{$this->first()}{$this->pre()}{$this->pres()}{$this->strlist()}{$this->nexts()}{$this->next()}{$this->end()}
                {$this->nowpage()}{$this->select()}{$this->input()}{$this->piclist()}";
            case 2 :
                return $this->pre() . $this->strlist() . $this->next();
            case 3 :
                return "<span class='total'>" . L("page_show_case1") . ":{$this->total_row}
                {$this->desc['unit']}&nbsp;</span>" . $this->piclist() . $this->select();
        }
    }

}