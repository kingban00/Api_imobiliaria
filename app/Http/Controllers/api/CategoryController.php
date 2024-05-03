<?php

namespace App\Http\Controllers\api;

use App\Api\ApiMessages;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

	private $category;

	public function __construct(Category $category)
	{
		$this->category = $category;
	}

	public function index()
	{
		try {
			$category = $this->category->paginate('10');
			return response()->json($category, 200);
		} catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage(), $th->getCode());
			dd($message);
            return response()->json($message->getMessage(),404);
        }
	}
	public function store(Request $request)
	{
		$data = $request->all();

		try{

			$category = $this->category->create($data);

			return response()->json([
				'data' => [
					'msg' => 'Categoria cadastrada com sucesso!'
				]
			], 200);

		} catch (\Exception $e) {
			$message = new ApiMessages($e->getMessage(), $e->getCode());
			return response()->json($message->getMessage(), 401);
		}
	}

	public function show($id)
	{
		try{

			$category = $this->category->findOrFail($id);

			return response()->json([
				'data' => $category
			], 200);

		} catch (\Exception $e) {
			$message = new ApiMessages($e->getMessage(), $e->getCode());
			return response()->json($message->getMessage(), 401);
		}
	}
	public function update(Request $request, $id)
	{
		$data = $request->all();

		try{

			$category = $this->category->findOrFail($id);
			$category->update($data);

			return response()->json([
				'data' => [
					'msg' => 'Categoria atualizada com sucesso!'
				]
			], 200);

		} catch (\Exception $e) {
			$message = new ApiMessages($e->getMessage(), $e->getCode());
			return response()->json($message->getMessage(), 401);
		}
	}

	public function destroy($id)
	{
		try{

			$category = $this->category->findOrFail($id);
			$category->delete();

			return response()->json([
				'data' => [
					'msg' => 'Categoria removida com sucesso!'
				]
			], 200);

		} catch (\Exception $e) {
			$message = new ApiMessages($e->getMessage(), $e->getCode());
			return response()->json($message->getMessage(), 401);
		}
	}

	public function realStates($id){
		try{
			$categories = $this->category->findOrFail($id);
			return response()->json($categories->realStates, 200);
		} catch(\Exception $e){
			$message = new ApiMessages($e->getMessage(), $e->getCode());
			return response()->json($message->getMessage(), 401);
		}
	}
}