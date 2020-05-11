<?php

declare(strict_types=1);
namespace HDNET\Importr\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * JSON de- and encode
 *
 * @author     Tim Spiekerkötter <tim.spiekerkoetter@hdnet.de>
 * @version    SVN: $Id$
 */
class JsonViewHelper extends AbstractViewHelper
{

    /**
     * @return string
     */
    public function render()
    {
        return \addslashes(\json_encode($this->renderChildren()));
    }
}
