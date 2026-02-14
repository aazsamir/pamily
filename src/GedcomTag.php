<?php

declare(strict_types=1);

namespace Aazsamir\Pamily;

/**
 * @see https://wiki.genealogy.net/GEDCOM-Tags
 */
enum GedcomTag: string
{
    case INDI = 'INDI';
    case FAM = 'FAM';
    case SOUR = 'SOUR';
    case VERS = 'VERS';
    case NAME = 'NAME';
    case TIME = 'TIME';
    case NOTE = 'NOTE';
    case HEAD = 'HEAD';
    case TRLR = 'TRLR';
    case DATE = 'DATE';
    case SUBM = 'SUBM';
    case FILE = 'FILE';
    case COPR = 'COPR';
    case GEDC = 'GEDC';
    case FORM = 'FORM';
    case CHAR = 'CHAR';
    case TYPE = 'TYPE';
    case GIVN = 'GIVN';
    case SURN = 'SURN';
    case SEX = 'SEX';
    case BIRT = 'BIRT';
    case CONT = 'CONT';
    case CHAN = 'CHAN';
    case CHIL = 'CHIL';
    case EVEN = 'EVEN';
    case WIFE = 'WIFE';
    case HUSB = 'HUSB';
    case PEDI = 'PEDI';
    case FAMC = 'FAMC';
    case FAMS = 'FAMS';
    case DEAT = 'DEAT';
    case MARR = 'MARR';
    case DIV = 'DIV';
    case CONC = 'CONC';
}