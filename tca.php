<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$TCA['tx_typo3roadmap_majorversion'] = array(
    'ctrl' => $TCA['tx_typo3roadmap_majorversion']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,starttime,endtime,title,bodytext,regularsupport,prioritysupport,extendedsupport,phpversions'
    ),
    'feInterface' => $TCA['tx_typo3roadmap_majorversion']['feInterface'],
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check',
                'default' => '0'
            )
        ),
        'starttime' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'default' => '0',
                'checkbox' => '0'
            )
        ),
        'endtime' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0',
                'range' => array(
                    'upper' => mktime(3, 14, 7, 1, 19, 2038),
                    'lower' => mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))
                )
            )
        ),
        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_majorversion.title',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'required,trim',
            )
        ),
        'bodytext' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_majorversion.bodytext',
            'config' => array(
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
                'wizards' => array(
                    '_PADDING' => 2,
                    'RTE' => array(
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'type' => 'script',
                        'title' => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
                        'icon' => 'wizard_rte2.gif',
                        'script' => 'wizard_rte.php',
                    ),
                ),
            )
        ),
        'developmentstart' => array(
            'exclude' => 0,
            'label' => 'Begin of Development',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
        'regularsupport' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_majorversion.regularsupport',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
        'prioritysupport' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_majorversion.prioritysupport',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
        'extendedsupport' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_majorversion.extendedsupport',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
        'phpversions' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_majorversion.phpversions',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'tx_typo3roadmap_phpversion',
                'foreign_table_where' => 'ORDER BY tx_typo3roadmap_phpversion.uid',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 10,
                "MM" => "tx_typo3roadmap_majorversion_phpversions_mm",
            )
        ),
        'minorversions' => array(
            'exclude' => 0,
            'label' => 'Minor versions',
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'tx_typo3roadmap_minorversion',
                'foreign_field' => 'parent'
            ),
        ),
    ),
    'types' => array(
        '0' => array(
            'showitem' => '
		hidden;;1;;1-1-1,
		title;;;;2-2-2,
		bodytext;;;richtext[]:rte_transform[mode=ts];3-3-3,
		--div--;Support Times,
		developmentstart,
		regularsupport,
		prioritysupport,
		extendedsupport,
		--div--;PHP Versions,
		phpversions,
		--div--;Minor Versions,
		minorversions
		'
        )
    ),
    'palettes' => array(
        '1' => array('showitem' => 'starttime, endtime')
    )
);


$TCA['tx_typo3roadmap_minorversion'] = array(
    'ctrl' => $TCA['tx_typo3roadmap_minorversion']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,version,link,focus,estimated,released,parent'
    ),
    'feInterface' => $TCA['tx_typo3roadmap_minorversion']['feInterface'],
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check',
                'default' => '0'
            )
        ),
        'version' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_minorversion.version',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'required,trim',
            )
        ),
        'link' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_minorversion.link',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'link_popup.gif',
                        'script' => 'browse_links.php?mode=wizard',
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ),
                ),
                'eval' => 'trim',
            )
        ),
        'focus' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_minorversion.focus',
            'config' => array(
                'type' => 'input',
                'size' => '30',
                'eval' => 'required,trim',
            )
        ),
        'estimated' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_minorversion.estimated',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
        'released' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_minorversion.released',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
        'parent' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_minorversion.parent',
            'config' => array(
                'type' => 'input',
                'size' => '4',
                'max' => '4',
                'eval' => 'int',
                'checkbox' => '0',
                'range' => array(
                    'upper' => '1000',
                    'lower' => '10'
                ),
                'default' => 0
            )
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'hidden;;1;;1-1-1, version, link, focus, estimated, released, parent')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);


$TCA['tx_typo3roadmap_phpversion'] = array(
    'ctrl' => $TCA['tx_typo3roadmap_phpversion']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'hidden,version,activesupport,securitysupport'
    ),
    'feInterface' => $TCA['tx_typo3roadmap_phpversion']['feInterface'],
    'columns' => array(
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => array(
                'type' => 'check',
                'default' => '0'
            )
        ),
        'version' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_phpversion.version',
            'config' => array(
                'type' => 'input',
                'size' => '30',
            )
        ),
        'activesupport' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_phpversion.activesupport',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
        'securitysupport' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:typo3_roadmap/locallang_db.xml:tx_typo3roadmap_phpversion.securitysupport',
            'config' => array(
                'type' => 'input',
                'size' => '8',
                'max' => '20',
                'eval' => 'date',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'hidden;;1;;1-1-1, version, activesupport, securitysupport')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    )
);