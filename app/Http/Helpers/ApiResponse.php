<?php

namespace App\Http\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse {
    protected function successResponse($message="successful",$content=null,$status=200) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'content' => $content,
            'status'  => $status
        ],$status);
    }

    protected function errorResponse($message="bad Request!",$status=400) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status'  => $status
        ],$status);
    }

    /**
     * Build a response array with metadata for paginated collection
     *
     * @param class-string<JsonResource> $resource a resource class string name
     * @param LengthAwarePaginator       $collection
     *
     * @return array
     */
    protected function buildPaginatedResourceResponse(string $resource, LengthAwarePaginator $collection): array
    {
        if (!is_subclass_of($resource, JsonResource::class)) {
            throw new \InvalidArgumentException(sprintf('%s must be a subclass of %s.', $resource, JsonResource::class));
        }

        return [
            'data' => $resource::collection($collection),
            'meta' => [
                'total'       => $collection->total(),
                'currentPage' => $collection->currentPage(),
                'lastPage'    => $collection->lastPage(),
                'perPage'     => $collection->perPage(),
            ],
            'links' => [
                'next' => $collection->nextPageUrl(),
                'prev' => $collection->previousPageUrl(),
            ],
        ];
    }
}
