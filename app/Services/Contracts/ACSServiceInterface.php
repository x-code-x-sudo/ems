<?php

namespace App\Services\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface ACSServiceInterface
{
    public function filter();

    public function customStore($request);

    public function customUpdate($id, $request);

    public function delete($id);

    public function createRecord(array $data);

    public function customFilter(array $filters);
}
