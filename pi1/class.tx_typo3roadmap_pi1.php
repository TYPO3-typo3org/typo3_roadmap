<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Mathias Schreiber <mathias.schreiber@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Plugin 'TYPO3 Roadmap' for the 'typo3_roadmap' extension.
 *
 * @author        Mathias Schreiber <mathias.schreiber@typo3.org>
 * @package       TYPO3
 * @subpackage    tx_typo3roadmap
 */
class tx_typo3roadmap_pi1 extends tslib_pibase
{
    public $prefixId = 'tx_typo3roadmap_pi1';        // Same as class name
    public $scriptRelPath = 'pi1/class.tx_typo3roadmap_pi1.php';    // Path to this script relative to the extension dir.
    public $extKey = 'typo3_roadmap';    // The extension key.
    public $pi_checkCHash = true;

    /**
     * @var Tx_Fluid_View_StandaloneView
     */
    protected $view;

    /**
     * Color Codes for the charts
     *
     * @var array
     */
    protected $colors = [
        'regular' => '#69A550',
        'security' => '#FF8700',
        'elts' => '#ffb767',
        'sprint' => '#e6e6e6',
        'stabilization' => '#983030'
    ];

    /**
     * @var t3lib_DB
     */
    protected $db;

    /**
     * @var t3lib_PageRenderer
     */
    protected $pageRenderer;

    /**
     * The main method of the Plugin.
     *
     * @param string $content The Plugin content
     * @param array  $conf    The Plugin configuration
     *
     * @return string The content that is displayed on the website
     */
    public function main($content = '', array $conf)
    {
        $this->conf = $conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();
        $this->initializeView();
        $this->db = $GLOBALS['TYPO3_DB'];
        $this->pageRenderer = $this->getFrontendController()->getPageRenderer();

        $majorVersions = $this->getMajorVersions();
        $phpVersions = $this->getAllAvailablePHPVersions();

        $this->renderCharts($majorVersions);

        $this->view->assign('majors', $majorVersions);
        $this->view->assign('ceUid', $this->cObj->data['uid']);
        $this->view->assign('phpVersions', $phpVersions);
        $this->view->assign('majorVersions', $majorVersions);

        $content .= $this->view->render();

        return $content;
    }

    /**
     * Initialize the Fluid StandAlone View and set the template
     */
    protected function initializeView()
    {
        $this->view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
        $this->view->setTemplatePathAndFilename(t3lib_extMgm::extPath($this->extKey) .
            '/Resources/Private/Templates/Roadmap.html');
    }

    /**
     * Fetches all major versions from the database
     *
     * @return array|NULL
     */
    protected function getMajorVersions()
    {
        $rows = $this->db->exec_SELECTgetRows(
            '*',
            'tx_typo3roadmap_majorversion',
            '1 = 1 ' . $this->cObj->enableFields('tx_typo3roadmap_majorversion'),
            'sorting'
        );
        foreach ($rows as $index => $singleRow) {
            foreach ($singleRow as $fieldName => $fieldValue) {
                if (t3lib_div::testInt($fieldValue)) {
                    $rows[$index][$fieldName] = (int)$fieldValue;
                }
            }
            $rows[$index]['minorversions'] = $this->getMinorVersions($singleRow['uid']);
            $rows[$index]['phpVersions'] = $this->getSupportedPHPVersions($singleRow['uid']);
        }

        return $rows;
    }

    /**
     * Fetches the minor versions of a given major version
     *
     * @param int $majorVersion The major version uid to fetch the minors for
     *
     * @return array|NULL
     */
    protected function getMinorVersions($majorVersion)
    {
        $rows = $this->db->exec_SELECTgetRows(
            '*',
            'tx_typo3roadmap_minorversion',
            'parent = ' . (int)$majorVersion . '' . $this->cObj->enableFields('tx_typo3roadmap_minorversion'),
            'sorting'
        );

        return $rows;
    }

    /**
     * @param int $majorVersion
     * @return array
     */
    protected function getSupportedPHPVersions($majorVersion)
    {
        $rows = $this->db->exec_SELECTgetRows(
            'uid, version',
            'tx_typo3roadmap_majorversion_phpversions_mm
  INNER JOIN tx_typo3roadmap_phpversion ON tx_typo3roadmap_phpversion.uid = tx_typo3roadmap_majorversion_phpversions_mm.uid_foreign',
        'tx_typo3roadmap_majorversion_phpversions_mm.uid_local = '.(int)$majorVersion . $this->cObj->enableFields('tx_typo3roadmap_phpversion'),
            '',
            '',
            '',
            'uid'
        );
        return $rows;
    }

    /**
     * @return array|NULL
     */
    protected function getAllAvailablePHPVersions()
    {
        $rows = $this->db->exec_SELECTgetRows(
            'uid, version',
            'tx_typo3roadmap_phpversion',
            '1 = 1 ' . $this->cObj->enableFields('tx_typo3roadmap_phpversion'),
            '',
            'version ASC',
            '',
            'uid'
        );
        if($rows === null) {
            return [];
        }
        return $rows;
    }

    /**
     * Renders the LTS charts
     *
     * @param array $majorVersions
     */
    protected function renderCharts(array $majorVersions)
    {
        $this->pageRenderer->addJsLibrary('amcharts', 'https://www.amcharts.com/lib/3/amcharts.js');
        $this->pageRenderer->addJsLibrary('amcharts_serial', 'https://www.amcharts.com/lib/3/serial.js');
        $this->pageRenderer->addJsLibrary('amcharts_gantt', 'https://www.amcharts.com/lib/3/gantt.js');
        $this->pageRenderer->addJsLibrary('amcharts_lightheme', 'https://www.amcharts.com/lib/3/themes/light.js');
        $this->pageRenderer->addJsLibrary('amcharts_export', 'https://www.amcharts.com/lib/3/plugins/export/export.js');
//        $this->pageRenderer->addCssFile('https://www.amcharts.com/lib/3/plugins/export/export.css');

        $data = $this->generateChartArray($majorVersions);
        $this->view->assign('data', $data);
        $chartJs = '
        AmCharts.useUTC = true;
        var today = new Date();
		var chart = AmCharts.makeChart("charts", {
			"type": "gantt",
			"theme": "light",
//			"marginRight": 70,
			"period": "YYYY",
			"dataDateFormat": "YYYY-MM-DD",
			"columnWidth": 0.65,
			"precision": 1,
			"valueAxis": {
				"type": "date",
				"autoGridCount": false,
				"gridCount": 24,
				"guides": [
				    {
					"value": today,
					"toValue": today,
					"lineAlpha": 1,
					"lineThickness": 1,
					"inside": true,
					"labelRotation": 90,
					"label": "Today",
					"above": true
				    }
				]
			},
			"graph": {
				"fillAlphas": 1,
				"balloonText": "<b>[[task]]</b>: [[open]]-[[value]]"
			},
			"rotate": true,
			"categoryField": "version",
			"segmentsField": "segments",
			"colorField": "color",
			"startDateField": "start",
			"endDateField": "end",
			"dataProvider": ' . json_encode($data) . ',
//			"valueScrollbar": {
//				"autoGridCount": true
//			},
			"chartCursor": {
				"cursorColor": "#55bb76",
				"valueBalloonsEnabled": false,
				"cursorAlpha": 0,
				"valueLineAlpha": 0.5,
				"valueLineBalloonEnabled": true,
				"valueLineEnabled": true,
				"zoomable": false,
				"valueZoomable": true
			},
			"export": {
				"enabled": true,
				"divId": "exportContainer",
                "position": "bottom-right",
                "fileName": "typo3-support-times",
				"menu": [
				    "PNG","PDF", "SVG"
				]
			}
		});
        ';
        $this->pageRenderer->addJsFooterInlineCode('ltsChart', $chartJs);
    }

    /**
     * Central date formatter
     *
     * @param int $timeStamp
     *
     * @return bool|string
     */
    protected function dateFromTimestamp($timeStamp = 0)
    {
        return date('Y-m-d', $timeStamp);
    }

    /**
     * Creates the raw JSON data for the chart
     * This is rather complex so take your time
     *
     * @param array $majorVersions
     *
     * @return array
     */
    protected function generateChartArray(array $majorVersions)
    {
        $data = [];
        foreach ($majorVersions as $index => $majorVersion) {
            $firstStart = (int)$majorVersion['developmentstart'];
            $data[$index]['version'] = $majorVersion['title'];
            foreach ($majorVersion['minorversions'] as $minorIndex => $minorVersion) {
                $realDate = (int)$minorVersion['estimated'];
                if ((int)$minorVersion['released'] >= (int)$minorVersion['estimated']) {
                    $realDate = (int)$minorVersion['released'];
                }

                // Check if we are in the first loop
                if ($minorIndex === 0) {
                    $sprintStart = $firstStart = (int)$majorVersion['developmentstart'];
                } else {
                    //@todo check correct field
                    $sprintStart = $majorVersion['minorversions'][$minorIndex-1]['estimated'];
                }

                //Sprint Segment
                $data[$index]['segments'][] = [
                    'start' => $this->dateFromTimestamp($sprintStart),
                    'end' => $this->dateFromTimestamp($realDate - 1209600),
                    'color' => $this->colors['sprint'],
                    'task' => 'Sprint Phase ' . $minorVersion['version']
                ];

                //Stabilization Segment
                $data[$index]['segments'][] = [
                    'start' => $this->dateFromTimestamp($realDate - 1209600),
                    'end' => $this->dateFromTimestamp($realDate),
                    'color' => $this->colors['stabilization'],
                    'task' => 'Stabilization Phase ' . $minorVersion['version']
                ];
                // Set the last release date as start of maintenance
                $firstStart = $realDate;
            }
            /**
             * Set Maintenance Times
             */
            // Regular Maintenance
            $data[$index]['segments'][] = [
                'start' => $this->dateFromTimestamp($firstStart),
                'end' => $this->dateFromTimestamp($majorVersion['regularsupport']),
                'color' => $this->colors['regular'],
                'task' => 'Regular Maintenance'
            ];
            // Security Maintenance
            $data[$index]['segments'][] = [
                'start' => $this->dateFromTimestamp($majorVersion['regularsupport']),
                'end' => $this->dateFromTimestamp($majorVersion['prioritysupport']),
                'color' => $this->colors['security'],
                'task' => 'Priority bugfixes'
            ];
            // ELTS Maintenance
            if ($majorVersion['extendedsupport'] > 0) {
                $data[$index]['segments'][] = [
                    'start' => $this->dateFromTimestamp($majorVersion['prioritysupport']),
                    'end' => $this->dateFromTimestamp($majorVersion['extendedsupport']),
                    'color' => $this->colors['elts'],
                    'task' => 'Extended support (optional)'
                ];
            }
        }

        return $data;
    }

    /**
     * @return tslib_fe
     */
    protected function getFrontendController()
    {
        return $GLOBALS['TSFE'];
    }

}