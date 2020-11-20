<?php

namespace Avado\MoodleAbstractionLibrary\Database;

class Builder extends \Illuminate\Database\Eloquent\Builder
{
    /**
     * @param null $perPage
     * @param array $columns
     * @param string $pageName
     * @param null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return parent::paginate($this->getCurrentPageLimit(), $columns, $pageName, $this->getCurrentPaginationPage());
    }

    /**
     *
     */
    protected function getCurrentPaginationPage()
    {
        return $_GET['page'] ?? 1;
    }

    /**
     * Get current page limit, defaulted to 15 records based on eloquent model.
     *
     * @return mixed|null
     */
    protected function getCurrentPageLimit()
    {
        return $_GET['limit'] ?? null;
    }
}
