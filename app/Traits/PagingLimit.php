<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait PagingLimit
{
    /**
     * Validate the limit for pagination.
     *
     * @param  \Illuminate\Http\Request $request
     * @return interger $limit
     */
    protected function pagingLimit(Request $request)
    {
        // Default limit
        $limit = 25;

        if ($request->has('limit')) {
            $this->validate($request, [
                'page' => 'nullable|integer',
                'limit' => 'nullable|integer|in:10,25,50,100',
            ]);

            $limit = (int) $request->query('limit');
        }

        return $limit;
    }
}
