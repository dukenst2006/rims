<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/11/2018
 * Time: 2:07 PM
 */

namespace Rims\App\ViewComposers;

use Illuminate\View\View;
use Rims\Domain\Levels\Models\Level;

class LevelsComposer
{
    /**
     * Holds list of levels from storage.
     *
     * @var
     */
    private $levels;

    /**
     * @param View $view
     * @return View
     */
    public function compose(View $view) {
        if(!$this->levels) {
            $this->levels = Level::where('usable', true)->get();
        }

        return $view->with('levels', $this->levels);
    }
}