<?php

namespace App\Http\Controllers;

use App\Http\Resources\NodeResource;
use App\Models\Node;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NodeController extends Controller
{
    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        if (is_null($node = Node::with('children')->find($id))) {
            return $this->sendResponse('Not found');
        }

        return $this->sendResponse('OK', new NodeResource($node));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addNode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|exists:nodes,id',
            'name' => 'required',
            'department_name' => 'nullable|string',
            'programming_language' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors()->all(), 422);
        }

        $validated = $validator->validated();
        $node = Node::create($validated);
        $node->load('children');

        return $this->sendResponse('Created', [], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateParentNode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:nodes,id',
            'parent_id' => 'required|different:id|exists:nodes,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors()->all(), 422);
        }

        if ($request->input('id') == 1) {
            return $this->sendError('Root node can\'t be updated', [], 400);
        }

        $node = Node::find($request->input('id'));
        $node->parent_id = $request->input('parent_id');
        $node->save();
        $node->fresh()->load('children');

        return $this->sendResponse('Updated', new NodeResource($node));
    }
}
