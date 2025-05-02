<?php

namespace InfyOm\Generator\Utils;

class SchemaUtil
{
    public static function createField($field): string
    {
        $fieldName = $field['fieldName'];
        $databaseInputStr = $field['databaseInputs'];

        $databaseInputs = explode(':', $databaseInputStr);

        $fieldTypeParams = explode(',', array_shift($databaseInputs));
        $fieldType = array_shift($fieldTypeParams);

        $fieldStr = '$table->' . $fieldType . "('" . $fieldName . "'";

        if ($fieldTypeParams !== []) {
            $fieldStr .= ', ' . implode(' ,', $fieldTypeParams);
        }

        if ($fieldType == 'enum') {
            $inputsArr = explode(',', $field['htmlTypeInputs']);
            $inputArrStr = GeneratorFieldsInputUtil::prepareValuesArrayStr($inputsArr);
            $fieldStr .= ', ' . $inputArrStr;
        }

        $fieldStr .= ')';
        foreach ($databaseInputs as $databaseInput) {
            $databaseInput = explode(',', $databaseInput);
            $type = array_shift($databaseInput);
            $fieldStr .= sprintf('->%s(', $type) . implode(',', $databaseInput) . ')';
        }

        return $fieldStr . ';';
    }
}
