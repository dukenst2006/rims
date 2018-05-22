<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/7/2018
 * Time: 1:51 AM
 */

namespace Rims\App\ViewComposers;

use Illuminate\View\View;
use Rims\Domain\Areas\Models\Area;

class AreasComposer
{
    /**
     * Holds list of areas from storage.
     *
     * @var $areas
     */
    private $areas;

    /**
     * Hold user selected area.
     *
     * @var $areas
     */
    private $area;

    public function compose(View $view)
    {
        if (!$this->areas) {
            $this->areas = Area::get()->toTree();
        }

        if (!$this->area) {
            $this->area = Area::where('slug', session()->get('area', 'Kenya'))->first();
        }

        return $view->with('areas', $this->areas)
            ->with('area', $this->area);
    }
}