<?php

namespace XoopsModules\Tdmcreate\Files\Language;

use XoopsModules\Tdmcreate;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class LanguageDefines.
 */
class LanguageDefines
{

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
    }

    /**
     * @static function getInstance
     * @param null
     * @return LanguageDefines
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function getAboveHeadDefines
     * @param string $string
     * @return string
     */
    public function getAboveHeadDefines($string)
    {
        return "// ---------------- {$string} ----------------\n";
    }

    /**
     * @public function getAboveDefines
     * @param string $string
     * @return string
     */
    public function getAboveDefines($string)
    {
        return "// {$string}\n";
    }

    /**
     * @public function getDefine
     * @param string $language
     * @param string $defined
     * @param string $description
     * @param bool   $usedoubleqoute
     * @return string
     */
    public function getDefine($language, $defined, $description, $usedoubleqoute = false)
    {
        $pc = Tdmcreate\Files\CreatePhpCode::getInstance();

        $defined = mb_strtoupper($defined);

        if ($usedoubleqoute) {
            $ret = $pc->getPhpCodeDefine("{$language}{$defined}", "\"{$description}\"");
        } else {
            $ret = $pc->getPhpCodeDefine("{$language}{$defined}", "'" . $description . "'");
        }

        return $ret;
    }

    /**
     * @public function getBelowDefines
     * @param string $string
     *
     * @return string
     */
    public function getBelowDefines($string)
    {
        return "// ---------------- {$string} ----------------";
    }

    /**
     * @public function getBlankLine
     *
     * @return string
     */
    public function getBlankLine()
    {
        return "\n";
    }
}
