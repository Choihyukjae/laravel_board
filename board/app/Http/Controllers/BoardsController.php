<?php
/********************
 * 프로젝트명 : larevel_board
 * 디렉토리   : Controllers
 * 파일명     : BoardsController.php
 * 이력       : v001 0526 최혁재 new
 *             v002 0530 최혁재  유효성 체크 추카
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Boards::select(['id','title','hits','created_at','updated_at'])->orderBy('hits','desc')->get();
        return view('list')->with('datas',$result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {   

        // v002 add start 
        $req->validate([
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:1000'
        ]);
        // v002 add end
        
        $boards = new Boards([
            'title' => $req->title
            ,'content' => $req->content

            
        ]);
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);
        $boards->hits++;
        $boards->save();
        return view('detail')->with('data', Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boards = Boards::find($id);
        return view('edit')->with('data', $boards);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {   

        // v002 add start 
        // ID를 리퀘스트객체에 머지
        $arr = ['id' => $id];
        $req->merge($arr);
        // $req->$request->add($arr);
        
        // $req->validate([
        //     'id' => 'required|interger'
        //     ,'title' => 'required|between:3,30'
        //     ,'content' => 'required|max:1000'
        // ]);
                // v002 add end

        // 유효성 검사 방법 2
        $validator = Validator::make(
            $req->only('id','title','content')
            ,[
                'id' => 'required|integer'
                ,'title' => 'required|between:3,30'
                ,'content' => 'required|max:1000'
            ]
        );

        if($validator->fails()) {
            return redirect()
            ->back()->withErrors($validator)->withInput($req->only('title','content'));
        }

        $result = Boards::find($id);
        $result->title = $req->title;
        $result->content = $req->content;
        $result->save();

        // $boards = Boards::find($id);
        // $boards -> update([
        //     'title' => $req->input('title')
        //     ,'content' => $req->input('content')   
        // ]);
        // $boards->save();
        // return view('detail')->with('data', Boards::findOrFail($id));
        return redirect('/boards/'.$id);//->with('data',Boards::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $boards = Boards::find($id);
        // $boards -> update([
        //     'deleted_at' => now()
        // ]);
        $boards->delete();
        return redirect('/boards');
    }
}
