<?php
/*------------------------------------------------------------------------
# mod_loadjquery25 - Load jQuery for Joomla!
# ------------------------------------------------------------------------
# author    Hiro Nozu
# copyright Copyright (C) 2011 Hiro Nozu. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://http://ideas.forjoomla.net
# Technical Support:  Contact - http://ideas.forjoomla.net/contact
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');


if ($params->get('test', '1')) {
    if (preg_match_all('#<jdoc:include\ type="([^"]+)" (.*)\/>#iU', file_get_contents(JPATH_THEMES.DS.$app->getTemplate().DS.'index.php'), $matches)) {
        if (count($matches[1])) {
            foreach ($matches[1] as $index => $type) {
                if ($type == 'modules') {
                    $attribs = JUtility::parseAttributes($matches[2][$index]);
                    if (isset($attribs['name'])) {
                        $message = 'Set this module as it is displayed at last in the module position "' . $attribs['name'] . '"';
                        echo '<div style="display: inline-block; width: 200px; background: #fff; margin: 4px; padding: 4px; border: 3px solid #ccc;">Load jQuery Module:<br />'.$message.'</div>';
                    }
                    break;
                }
            }
        }else{
            echo 'Not Found (1)';
        }
    }else{
        echo 'Not Found (2)';
    }
    return;
}


$doc = JFactory::getDocument();

$source = $params->get('source', 'jquery-1.6.2.min.js');

$dataNew['scripts'] = array(JURI::root().'modules/mod_loadjquery25/'.$source => array(
    'mime' => 'text/javascript',
    'defer' => false,
    'async' => false,
));

$data = $doc->getHeadData();

if (count($data['scripts'])) {
    $filter  = implode('|', explode("\n", preg_replace('/\r/', '', $params->get('include'))));
    $uriRoot = preg_replace('/\//', '\/', JURI::root(true));
    $noMootools = ($params->get('mootools', '1') == '0');
    foreach($data['scripts'] as $script => $type) {
        if (strpos($script, 'jquery') === false) {
            if ($noMootools and ! preg_match('#'.$filter.'#i', $script)) continue;
        }else{
            if ( ! preg_match('#'.$filter.'#i', $script)) continue;
        }
        if ( ! preg_match('/^http/', $script)) // To make CssjsCompressor work
            $script = JURI::root() . preg_replace('/^\//', '', preg_replace('/'.$uriRoot.'/', '', $script));
        $dataNew['scripts'][$script] = $type;
    }
}

// var_dump($dataNew); exit;

/*
if (count($data['styleSheets'])) {
    $dataNew['styleSheets'] = array();
    $uriRoot = preg_replace('/\//', '\/', JURI::root(true));
    foreach($data['styleSheets'] as $file => $type) {
        if (!preg_match('/^http/', $file)) // To make CssjsCompressor work
            $file = JURI::root() . preg_replace('/^\//', '', preg_replace('/'.$uriRoot.'/', '', $file));
        $dataNew['styleSheets'][$file] = $type;
    }
}
// var_dump($data['styleSheets']);
// var_dump($dataNew['styleSheets']); exit;
*/

$doc->setHeadData($dataNew);
