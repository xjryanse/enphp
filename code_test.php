<?php
error_reporting(E_ERROR);
include './func_v2.php';
$_SERVER['starttime'] = microtime(1);
$starttime            = explode(' ', $_SERVER['starttime']);
$_SERVER['time']      = $starttime[1];

ob_implicit_flush(1);
//$dir = __DIR__ . '/code_test/';
$options = getopt('', ['no-obscure-var', 'keep-comment', 'keep-blank-line', 'path::', 'save-path::']);
// 混淆路径
$dir = isset($options['path']) ? $options['path'] : __DIR__ . DIRECTORY_SEPARATOR . 'code_test';
$dir = 'D:\\phpstudy_pro\\WWW\\new_qzs_ydzb';//写死
// 保存路径
$save_path = isset($options['save-path']) ? $options['save-path'] : $dir . '_obscure';
$save_path = "D:\\phpstudy_pro\\WWW\\new_qzs_ydzb";//写死

//$files     = glob($dir . '*.php');
$files = [];
get_dir($dir, $files);
$gen_count = 0;
chdir($dir);


    $options = array(
        //混淆方法名 1=字母混淆 2=乱码混淆
        'ob_function'        => 2,
        //混淆函数产生变量最大长度
        'ob_function_length' => 3,
        //混淆函数调用 1=混淆 0=不混淆 或者 array('eval', 'strpos') 为混淆指定方法
        'ob_call'            => 1,
        //随机插入乱码
        'insert_mess'        => 0,
        //混淆函数调用变量产生模式  1=字母混淆 2=乱码混淆
        'encode_call'        => 2,
        //混淆class
        'ob_class'           => 0,
        //混淆变量 方法参数  1=字母混淆 2=乱码混淆
        'encode_var'         => 2,
        //混淆变量最大长度
        'encode_var_length'  => 5,
        //混淆字符串常量  1=字母混淆 2=乱码混淆
        'encode_str'         => 2,
        //混淆字符串常量变量最大长度
        'encode_str_length'  => 3,
        // 混淆html 1=混淆 0=不混淆
        'encode_html'        => 2,
        // 混淆数字 1=混淆为0x00a 0=不混淆
        'encode_number'      => 1,
        // 混淆的字符串 以 gzencode 形式压缩 1=压缩 0=不压缩
        'encode_gz'          => 1,
        // 加换行（增加可阅读性）
        'new_line'           => 0,
        // 移除注释 1=移除 0=保留
        'remove_comment'     => 1,
        // debug
        'debug'              => 1,
        // 重复加密次数，加密次数越多反编译可能性越小，但性能会成倍降低
        'deep'               => 1,
        // PHP 版本
        'php'                => 7,
    );

foreach ($files as $file) {
    $target_file = $file;
    // 替换路径
    $target_file = str_replace('\\', '/', $target_file);
    //不存在则创建目录
    $tmpDir = dirname( $target_file );
    if (!file_exists($dir)) mkdir($tmpDir, 0777, true);
    
    // encode target
    enphp_file($file, $target_file, $options);
}


/**
 * 递归获取指定目录下的所有文件
 *
 * @param $path
 * @param &$file
 * @param $remove
 * @param $type
 * @return void
 * @author sunshine
 */
function get_dir($path, & $file, $remove = [], $type = ['php'])
{
    if (!file_exists($path)) return;
    if (is_file($path)) {
        if (!in_array($path, $remove) && ($type != null && in_array(get_file_ext($path), $type))) array_push($file, $path);
    } else {
        $handle = opendir($path);
        while (($f = readdir($handle)) != '') {
            if ($f != '.' && $f != '..' && $f != '' && $f != '.svn') get_dir($path . '/' . $f, $file, $remove, $type);
        }
        closedir($handle);
    }
}

/**
 * 获取文件后缀名
 *
 * @param string $file
 * @return string
 * @author sunshine
 */
function get_file_ext($file)
{
    return substr(strrchr($file, '.'), 1);
}


?>