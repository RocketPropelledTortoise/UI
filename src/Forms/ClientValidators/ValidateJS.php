<?php namespace Rocket\UI\Forms\ClientValidators;

class ValidateJS extends ClientValidator
{
    public function validChecks()
    {
        // Not supported: valid_emails, alpha_dash, decimal, is_natural_no_zero, is_file_type

        return [
            self::$REQUIRED, //required
            self::$EMAIL, //valid_email
            self::$ALPHA, //alpha
            self::$ALNUM, //alpha_numeric
            self::$NUM, //numeric
            self::$INT, //integer
            self::$DIGIT, //is_natural
            self::$IP, //valid_ip
            self::$BASE64, //valid_base64
            self::$CREDIT_CARD, //valid_credit_card
            self::$URL, //valid_url

            //with parameters
            self::$MIN_LENGTH, //min_length
            self::$MAX_LENGTH, //max_length
            self::$LENGTH, //exact_length
            self::$MIN, //greater_than
            self::$MAX, //less_than
            self::$EQUALTO, //matches
        ];
    }
}
