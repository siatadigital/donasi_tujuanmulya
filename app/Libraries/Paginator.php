<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Request;

class Paginator
{
    public $query;
    public $total;
    public $perPage;
    public $pageName;
    public $currentPage;
    public $url;
    public $parameters;
    public $displayLimit;


    function __construct()
    {
        $this->query = null;
        $this->perPage = 10;
        $this->pageName = 'page';
        $this->total = 0;
        $this->currentPage = 1;
        $this->displayLimit = 8;
        $this->url = Request::url();
        $this->parameters = Request::query();
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setPageName($pageName)
    {
        $this->pageName = $pageName;

        return $this;
    }

    public function setQuery($query)
    {
        $this->query = $query;
        $this->total = $this->getTotal($query);

        return $this;
    }

    public function setCurrentPage($page = 1)
    {
        $this->currentPage = $page;
        return $this;
    }

    public function getTotal($query = null)
    {
        if (is_null($query)) {
            return $this->total;
        }
        $bindings = $query->getBindings();

        $sql = $query->toSql();

        foreach ($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        $sql = str_replace('\\', '\\\\', $sql);

        $total = \DB::select(\DB::raw("select count(*) as total_count from ($sql) as count_table"));

        return $total[0]->total_count;
    }

    public function getData()
    {
        if (is_null($this->query)) {
            return [];
        }

        $skip = ($this->currentPage - 1) * $this->perPage;

        return $this->query->skip($skip)->take($this->perPage)->get();
    }

    public function links($url = null)
    {
        $pagesCount = ceil($this->total / $this->perPage);

        if ($pagesCount == 1 || $pagesCount == 0) {
            return '';
        }

        $ul = '<ul class="pagination">';
        $_ul = '</ul>';

        $li = '';

        $parameters = $this->parameters;
        $showPrev = $this->currentPage > 1;
        $showNext = $this->currentPage < $pagesCount;
        $showPrevJump = $this->currentPage > $this->displayLimit;
        $showNextJump = $this->currentPage <= $pagesCount - $this->displayLimit;
        $start = 1;
        $end = $pagesCount;

        if($pagesCount > $this->displayLimit) {
            $halfLimit = floor($this->displayLimit / 2);
            if($this->currentPage - $halfLimit >= 1) {
                $start = $this->currentPage - $halfLimit;
                if($start > $pagesCount - $this->displayLimit) {
                    $start = $pagesCount - $this->displayLimit;
                }
            }
            if ($start + $this->displayLimit <= $pagesCount) {
                $end = $start + $this->displayLimit;
            }
        }

        //show previous
        if($showPrev == true) {
            $parameters[$this->pageName] = ($this->currentPage - 1);
            $url = $this->url . '?' . http_build_query($parameters, '', '&');

            $li .= '<li><a href="' . $url . '">&lt;</a></li>';
        }

        // show previous jump
        if($showPrevJump == true) {
            $parameters[$this->pageName] = 1;
            $url = $this->url . '?' . http_build_query($parameters, '', '&');

            $li .= '<li><a href="' . $url . '">1</a></li>';
            $li .= '<li><a href="#">...</a></li>';
        }

        //show pages
        for ($i = $start; $i <= $end; $i++) {
            $active = $this->currentPage == $i ? 'active' : '';

            $parameters[$this->pageName] = $i;

            $url = $this->currentPage == $i ? '#' : ($this->url . '?' . http_build_query($parameters, '', '&'));

            $li .= '<li class="' . $active . '"><a href="' . $url . '">' . $i . '</a></li>';
        }

        // show next jump
        if($showNextJump == true) {
            $parameters[$this->pageName] = $pagesCount;
            $url = $this->url . '?' . http_build_query($parameters, '', '&');

            $li .= '<li><a href="#">...</a></li>';
            $li .= '<li><a href="' . $url . '">'. $pagesCount .'</a></li>';
        }

        //show next
        if($showNext == true) {
            $parameters[$this->pageName] = ($this->currentPage + 1);

            $url = $this->url . '?' . http_build_query($parameters, '', '&');

            $li .= '<li><a href="' . $url . '">&gt;</a></li>';
        }

        $html = $ul . $li . $_ul;

        return $html;
    }
}
