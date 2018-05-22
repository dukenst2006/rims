<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/11/2018
 * Time: 1:50 PM
 */

namespace Rims\App\ViewComposers;

use Illuminate\View\View;
use Rims\Domain\Education\Models\Education;

class EducationComposer
{
    /**
     * Holds list of education levels from storage.
     *
     * @var
     */
    private $education;

    /**
     * @param View $view
     * @return View
     */
    public function compose(View $view) {
        if(!$this->education) {
            $this->education = Education::where('usable', true)->get();
        }

        return $view->with('education_levels', $this->education);
    }
}