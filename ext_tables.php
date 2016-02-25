<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}
if (!isset($_EXTKEY)) {
    $_EXTKEY = 'typo3_roadmap';
}
$TCA['tx_typo3roadmap_majorversion'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_majorversion',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'dividers2tabs' => 1,
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_typo3roadmap_majorversion.gif',
    ),
);

$TCA['tx_typo3roadmap_minorversion'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_minorversion',
        'label' => 'version',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_typo3roadmap_minorversion.gif',
    ),
);

$TCA['tx_typo3roadmap_phpversion'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_phpversion',
        'label' => 'version',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_typo3roadmap_phpversion.gif',
    ),
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
    'LLL:EXT:typo3_roadmap/locallang_db.xml:tt_content.list_type_pi1',
    $_EXTKEY . '_pi1',
    t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
), 'list_type');
