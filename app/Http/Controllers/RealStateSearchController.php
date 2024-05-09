<?php

namespace App\Http\Controllers;
use App\Models\RealState;

use App\Repository\RealStateRepository;
use Illuminate\Http\Request;

class RealStateSearchController extends Controller
{

    private $realState;
    
    public function __construct(RealState $realState){
        $this->realState = $realState;
    }
    public function index(Request $request)
    {
        // $realState = $this->realState->orderBy("id","desc")->paginate(10);
        $repository = new RealStateRepository($this->realState);

        $repository->setLocation($request->all(['city', 'state']));
        if($request->has('conditions'))
            $repository->selectConditions($request->get('conditions'));
        if($request->has('fields'))
            $repository->selectFilters($request->get('fields'));
        return response()->json(['data' => $repository->getResults()->paginate(10)], 200);
    }

    public function show(string $id)
    {
        try{
            $realState = $this->realState->with('address')->with('photos')->findOrFail($id)->makeHidden('thumb');
            return response()->json(['data'=> $realState],200);
        }catch(\Exception $e){
            return response()->json(['message'=> $e->getMessage()],404);
        }
    }

}