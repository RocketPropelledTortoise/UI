<?php namespace Rocket\UI\Forms\ClientValidators;

class ParsleyJS extends ClientValidator
{
    public function validChecks()
    {
        // Not supported: pattern, mincheck, maxcheck, check

        return [
            self::$REQUIRED, //required
            self::$EMAIL,
            self::$NUM,
            self::$INT,
            self::$DIGIT,
            self::$ALNUM,
            self::$URL,

            //with parameters
            self::$MIN_LENGTH,
            self::$MAX_LENGTH,
            self::$LENGTH_RANGE,
            self::$MIN,
            self::$MAX,
            self::$RANGE,
            self::$EQUALTO,
        ];
    }
}
