<?php namespace Rocket\UI\Forms\ClientValidators;


abstract class ClientValidator
{
    static $REQUIRED = "REQUIRED";
    static $EMAIL = "EMAIL";
    static $ALPHA = "ALPHA";
    static $ALNUM = "ALNUM";
    static $NUM = "NUM";
    static $INT = "INT";
    static $DIGIT = "DIGIT";
    static $URL = "URL";
    static $BASE64 = "BASE64";
    static $CREDIT_CARD = "CREDIT_CARD";
    static $IP = "IP";

    //With params
    static $MIN_LENGTH = "MIN_LENGTH";
    static $MAX_LENGTH = "MAX_LENGTH";
    static $LENGTH_RANGE = "LENGTH_RANGE";
    static $LENGTH = "LENGTH";
    static $MIN = "MIN";
    static $MAX = "MAX";
    static $RANGE = "RANGE";
    static $EQUALTO = "EQUALTO";

    abstract public function validChecks();
}
