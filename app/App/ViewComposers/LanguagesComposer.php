<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/11/2018
 * Time: 4:17 PM
 */

namespace Rims\App\ViewComposers;

use Illuminate\View\View;
use Rims\Domain\Languages\Models\Language;

class LanguagesComposer
{
    /**
     * Holds list of languages from storage.
     *
     * @var
     */
    private $languages;

    /**
     * @param View $view
     * @return View
     */
    public function compose(View $view)
    {
        if(!$this->languages) {
            $this->languages = Language::get()->toTree();
        }
        
        return $view->with('languages', $this->languages);
    }
}