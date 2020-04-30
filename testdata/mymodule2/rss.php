<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * My Module 2 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule2
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use Xmf\Request;

require __DIR__ . '/header.php';

$cid = Request::getInt('cid', 0, 'GET');
include_once XOOPS_ROOT_PATH.'/class/template.php';
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
//header ('Content-Type:text/xml; charset=UTF-8');
$xoopsModuleConfig['utf8'] = false;

$tpl = new \XoopsTpl();
$tpl->xoops_setCaching(2); //1 = Cache global, 2 = Cache individual (for template)
$tpl->xoops_setCacheTime($helper->getConfig('timecacherss')*60); // Time of the cache on seconds
$categories = mymodule2MyGetItemIds('mymodule2_view', 'mymodule2');
$criteria = new \CriteriaCompo();

$criteria->add(new \Criteria('cat_status', 0, '!='));
$criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
if ($cid != 0){
    $criteria->add(new \Criteria('cid', $cid));
    $testfields = $testfieldsHandler->get($cid);
    $title = $xoopsConfig['sitename'] . ' - ' . $xoopsModule->getVar('name') . ' - ' . $testfields->getVar('tf_status');
} else {
    $title = $xoopsConfig['sitename'] . ' - ' . $xoopsModule->getVar('name');
}
$criteria->setLimit($helper->getConfig('perpagerss'));
$criteria->setSort('date');
$criteria->setOrder('DESC');
$testfieldsArr = $testfieldsHandler->getAll($criteria);
unset($criteria);

if (!$tpl->is_cached('db:mymodule2_rss.tpl', $cid)) {
    $tpl->assign('channel_title', htmlspecialchars($title, ENT_QUOTES));
    $tpl->assign('channel_link', XOOPS_URL.'/');
    $tpl->assign('channel_desc', htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES));
    $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
    $tpl->assign('channel_editor', $xoopsConfig['adminmail']);
    $tpl->assign('channel_category', 'Event');
    $tpl->assign('channel_generator', 'XOOPS - ' . htmlspecialchars($xoopsModule->getVar('tf_status'), ENT_QUOTES));
    $tpl->assign('channel_language', _LANGCODE);
    if ( _LANGCODE == 'fr' ) {
        $tpl->assign('docs', 'http://www.scriptol.fr/rss/RSS-2.0.html');
    } else {
        $tpl->assign('docs', 'http://cyber.law.harvard.edu/rss/rss.html');
    }
    $tpl->assign('image_url', XOOPS_URL . $xoopsModuleConfig['logorss']);
    $dimention = getimagesize(XOOPS_ROOT_PATH . $xoopsModuleConfig['logorss']);
    if (empty($dimention[0])) {
        $width = 88;
    } else {
       $width = ($dimention[0] > 144) ? 144 : $dimention[0];
    }
    if (empty($dimention[1])) {
        $height = 31;
    } else {
        $height = ($dimention[1] > 400) ? 400 : $dimention[1];
    }
    $tpl->assign('image_width', $width);
    $tpl->assign('image_height', $height);
    foreach (array_keys($testfieldsArr) as $i) {
        $description = $testfieldsArr[$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (strpos($description,'[pagebreak]')==false){
            $description_short = $description;
        } else {
            $description_short = substr($description,0,strpos($description,'[pagebreak]'));
        }
        $tpl->append('items', array('title' => htmlspecialchars($testfieldsArr[$i]->getVar('tf_status'), ENT_QUOTES),
                                    'link' => XOOPS_URL . '/modules/mymodule2/single.php?cid=' . $testfieldsArr[$i]->getVar('cid') . '&amp;tf_id=' . $testfieldsArr[$i]->getVar('tf_id'),
                                    'guid' => XOOPS_URL . '/modules/mymodule2/single.php?cid=' . $testfieldsArr[$i]->getVar('cid') . '&amp;tf_id=' . $testfieldsArr[$i]->getVar('tf_id'),
                                    'pubdate' => formatTimestamp($testfieldsArr[$i]->getVar('date'), 'rss'),
                                    'description' => htmlspecialchars($description_short, ENT_QUOTES)));
    }
}
header('Content-Type:text/xml; charset=' . _CHARSET);
$tpl->display('db:mymodule2_rss.tpl', $cid);