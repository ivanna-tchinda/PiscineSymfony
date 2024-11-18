<?php

namespace App\Enum;

enum Position: string
{
    case MAN = 'manager';
    case AC_MAN = 'account_manager';
    case QA_MAN = 'qa_manager';
    case DEV_MAN = 'dev_manager';
    case CEO = 'ceo';
    case COO = 'coo';
    case BE_DEV = 'backend_dev';
    case FE_DEV = 'frontend_dev';
    case QA_T = 'qa_tester';
}