<?php namespace Rocket\UI\Forms\ClientValidators;

abstract class ClientValidator
{
    public static $REQUIRED = 'REQUIRED';
    public static $EMAIL = 'EMAIL';
    public static $ALPHA = 'ALPHA';
    public static $ALNUM = 'ALNUM';
    public static $NUM = 'NUM';
    public static $INT = 'INT';
    public static $DIGIT = 'DIGIT';
    public static $URL = 'URL';
    public static $BASE64 = 'BASE64';
    public static $CREDIT_CARD = 'CREDIT_CARD';
    public static $IP = 'IP';

    //With params
    public static $MIN_LENGTH = 'MIN_LENGTH';
    public static $MAX_LENGTH = 'MAX_LENGTH';
    public static $LENGTH_RANGE = 'LENGTH_RANGE';
    public static $LENGTH = 'LENGTH';
    public static $MIN = 'MIN';
    public static $MAX = 'MAX';
    public static $RANGE = 'RANGE';
    public static $EQUALTO = 'EQUALTO';

    abstract public function validChecks();
}
