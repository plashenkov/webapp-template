<?php

namespace App\Lib\DB;

class Statement extends \PDOStatement
{
    /**
     * @inheritdoc
     * @param mixed $inputParameters
     * @return $this
     */
    public function execute($inputParameters = [])
    {
        if (func_num_args() > 1) {
            $inputParameters = func_get_args();
        } elseif (!is_array($inputParameters)) {
            $inputParameters = [$inputParameters];
        }

        parent::execute($inputParameters);

        return $this;
    }
}
