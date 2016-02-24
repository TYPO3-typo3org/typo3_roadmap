<?php

class Tx_Typo3Roadmap_ViewHelpers_Condition_InArrayViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper
{
    /**
     * @param string $needle
     * @param array $array
     * @param null $arrayPath
     * @return string
     */
    public function render($needle, array $array, $arrayPath = null)
    {
        if ($this->inArray($needle, $array, $arrayPath)) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }

    /**
     * @param string $needle
     * @param array $array
     * @param $arrayPath
     * @return bool
     */
    protected function inArray($needle, array $array, $arrayPath)
    {
        foreach ($array as $key => $value) {
            if ($arrayPath !== null) {
                $arrayValue = Tx_Extbase_Reflection_ObjectAccess::getPropertyPath($value, $arrayPath);
                if ($needle === $arrayValue) {
                    return true;
                }
            } elseif ($needle === $value) {
                return true;
            }
        }

        return false;
    }
}