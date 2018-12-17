<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Auth\Access\Gate;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class GateFilter implements FilterInterface
{
    protected $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function transform($item, Builder $builder)
    {

        if(is_array($item['can'])){
            if (! $this->arrayIsVisible($item)) {
                return false;
            }else{
                 return $item;
            }
        }else{
            if (! $this->isVisible($item)) {
                return false;
            }
        }

        return $item;
    }

    protected function arrayIsVisible($item){
        if(!$this->gate->allows($item['can'][0])) return false;
        for($x = 0; $x < count($item['can']); $x++){
            if(auth()->user()->hasRole($item['can'][$x])) return true;
        }
    }

    protected function isVisible($item)
    {
        if (! isset($item['can'])) {
            return true;
        }

        if (isset($item['model'])) {
            return $this->gate->allows($item['can'], $item['model']);
        }

        return $this->gate->allows($item['can']);
    }
}
